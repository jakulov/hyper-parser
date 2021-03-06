<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 03.06.16
 * Time: 23:35
 */
namespace jakulov\HyperParser;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use jakulov\HyperParser\Bridge\SunraDOMParserBridge;
use Psr\Http\Message\ResponseInterface;

/**
 * @link https://github.com/jakulov/hyper-parser
 *
 * Class Parser
 * @package jakulov\HyperParser
 */
class Parser
{
    public $allowFilters = [
        'text',
        'innertext',
        'outertext',
    ];
    /** @var array */
    public $httpOptions = [];
    /** @var bool */
    public $ignoreHttpStatusCode = false; // parse content even if response status code != 200
    /** @var string */
    public $useDOMParser = 'sunra';
    /** @var array */
    protected $DOMParserClasses = [
        'sunra' => SunraDOMParserBridge::class,
    ];
    /** @var DOMParserInterface */
    protected $DOMParser;
    /** @var ClientInterface */
    protected $httpClient;

    /**
     * Parser constructor.
     * @param DOMParserInterface $DOMParser
     * @param array $httpOptions
     */
    public function __construct(DOMParserInterface $DOMParser = null, array $httpOptions = [])
    {
        $this->DOMParser = $DOMParser;
        $this->httpOptions = $httpOptions;
    }

    /**
     * @param $url
     * @param array $pattern
     * @return array
     * @throws HyperParserException
     */
    public function parseUrl($url, array $pattern)
    {
        $response = $this->fetchUrl($url);
        if($this->isResponseCanBeParsed($response)) {
            return $this->extractDataByPattern($response->getBody()->getContents(), $pattern);
        }

        throw new HyperParserException(
            'Refused to parse response with status code = '. $response->getStatusCode() .'. url='. $url
        );
    }

    /**
     * @param array $urls
     * @param array $pattern
     * @param bool $failOnError
     * @return array
     * @throws HyperParserException
     */
    public function bulkParse(array $urls, array $pattern, $failOnError = true)
    {
        $data = [];
        $promises = [];
        $httpClient = $this->getHttpClient();
        foreach($urls as $url) {
            $promises[$url] = $httpClient->requestAsync('GET', $url);
        }

        $responses = \GuzzleHttp\Promise\settle($promises)->wait();
        foreach($responses as $url => $response) {
            $data[$url] = $this->parseResponse($response['value'], $pattern, $failOnError);
        }

        return $data;
    }

    /**
     * @param $response
     * @param array $pattern
     * @param bool $failOnError
     * @return array|string
     * @throws HyperParserException
     */
    protected function parseResponse($response, array $pattern = [], $failOnError = true)
    {
        if($response instanceof ResponseInterface && $this->isResponseCanBeParsed($response)) {
            return $this->extractDataByPattern($response->getBody()->getContents(), $pattern);
        }
        else {
            if($failOnError) {
                throw new HyperParserException(
                    $response instanceof \Exception ? $response->getMessage() : 'Cannot parse response',
                    $response instanceof \Exception ? $response->getCode() : 0,
                    $response instanceof \Exception ? $response : null
                );
            }
            return $response instanceof \Exception ? $response->getMessage() : 'Cannot parse response';
        }
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetchUrl($url, $method = 'GET', array $options = [])
    {
        return $this->getHttpClient()->request($method, $url, $options);
    }

    /**
     * @param string $content
     * @param array $pattern
     * @return array
     */
    public function extractDataByPattern($content, array $pattern)
    {
        $data = [];
        $dom = $this->getDOMParser()->getDOM($content);
        foreach($pattern as $field => $query) {
            if(is_array($query)) {
                $data[$field] = [];
                $elements = $dom->find($query['selector']);
                foreach($elements as $element) {
                    $item = [];
                    foreach($query['fields'] as $itemField => $fieldQuery) {
                        $item[$itemField] = $this->parseFieldFromDOM($element, $fieldQuery);
                    }

                    $data[$field][] = $item;
                }
            }
            else {
                $data[$field] = $this->parseFieldFromDOM($dom, $query);
            }
        }

        return $data;
    }

    /**
     * @param DOMInterface $dom
     * @param $query
     * @return array
     */
    protected function parseFieldFromDOM($dom, $query)
    {
        $filter = 'text';
        $selector = $query;
        if(stripos($query, '|') !== false) {
            list($selector, $filter) = explode('|', $query);
        }
        $field = [];
        $elements = $dom->find($selector);
        foreach($elements as $element) {
            if(in_array($filter, $this->allowFilters)) {
                $field[] = call_user_func_array([$element, $filter], []);
            }
            else {
                $field[] = call_user_func_array([$element, 'getAttribute'], [$filter]);
            }
        }
        if(!$field) {
            $field[] = null;
        }

        return $field;
    }

    /**
     * @return DOMParserInterface
     * @throws HyperParserException
     */
    public function getDOMParser()
    {
        if($this->DOMParser === null) {
            $domParserClass = isset($this->DOMParserClasses[$this->useDOMParser]) ?
                $this->DOMParserClasses[$this->useDOMParser] : null;
            if($domParserClass === null) {
                throw new HyperParserException('Unknown DOM Parser alias: '. $this->useDOMParser);
            }

            $this->DOMParser = new $domParserClass;
        }

        return $this->DOMParser;
    }

    /**
     * @param DOMParserInterface $DOMParser
     * @return $this
     */
    public function setDOMParser($DOMParser)
    {
        $this->DOMParser = $DOMParser;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if($this->httpClient === null) {
            $this->httpClient = new Client($this->httpOptions);
        }

        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param string $useDOMParser
     * @return $this
     */
    public function setUseDOMParser($useDOMParser)
    {
        $this->useDOMParser = $useDOMParser;

        return $this;
    }

    /**
     * @param $alias
     * @param $class
     * @return $this
     */
    public function addDOMParserClass($alias, $class)
    {
        $this->DOMParserClasses[$alias] = $class;

        return $this;
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    public function isResponseCanBeParsed(ResponseInterface $response)
    {
        return $response->getStatusCode() === 200 || $this->ignoreHttpStatusCode;
    }


}
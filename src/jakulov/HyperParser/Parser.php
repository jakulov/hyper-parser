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

    public function bulkParse(array $urls, array $pattern)
    {
        $data = [];

        // TODO: implement bulk

        return $data;
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

        // TODO: tree filters

        $dom = $this->getDOMParser()->getDOM($content);
        foreach($pattern as $field => $query) {
            $filter = 'text';
            $selector = $query;
            if(stripos($query, '|') !== false) {
                list($selector, $filter) = explode('|', $query);
            }
            $data[$field] = [];
            $elements = $dom->find($selector);
            foreach($elements as $element) {
                if(in_array($filter, $this->allowFilters)) {
                    $data[$field][] = call_user_func_array([$element, $filter], []);
                }
                else {
                    $data[$field][] = call_user_func_array([$element, 'getAttribute'], [$filter]);
                }
            }
        }

        return $data;
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
    protected function isResponseCanBeParsed(ResponseInterface $response)
    {
        return $response->getStatusCode() === 200 || $this->ignoreHttpStatusCode;
    }


}
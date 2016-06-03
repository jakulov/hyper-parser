<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 03.06.16
 * Time: 23:35
 */
namespace jakulov\HyperParser;

use jakulov\HyperParser\Bridge\SunraDOMParserBridge;

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

    /** @var DOMParserInterface */
    protected $DOMParser;

    /**
     * Parser constructor.
     * @param DOMParserInterface $DOMParser
     */
    public function __construct(DOMParserInterface $DOMParser = null)
    {
        $this->DOMParser = $DOMParser;
    }

    public function parserUrl($url, array $pattern)
    {
        $data = [];

        // TODO: implement parse

        return $data;
    }

    public function bulkParse(array $urls, array $pattern)
    {
        $data = [];

        // TODO: implement bulk

        return $data;
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
     */
    public function getDOMParser()
    {
        if($this->DOMParser === null) {
            $this->DOMParser = new SunraDOMParserBridge();
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


}
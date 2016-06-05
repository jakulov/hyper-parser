<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 0:08
 */

namespace jakulov\HyperParser\Bridge;

use jakulov\HyperParser\DOMInterface;
use jakulov\HyperParser\DOMParserInterface;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * Class SunraDOMParserBridge
 * @package jakulov\HyperParser
 */
class SunraDOMParserBridge implements DOMParserInterface
{
    /**
     * @param $string
     * @return DOMInterface
     */
    public function getDOM($string)
    {
        return HtmlDomParser::str_get_html($string);
//        return \simplehtmldom_1_5\str_get_html($string);
    }
}
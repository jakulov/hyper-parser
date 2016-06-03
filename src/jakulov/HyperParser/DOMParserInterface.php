<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 0:00
 */

namespace jakulov\HyperParser;

/**
 * Interface DOMParserInterface
 * @package jakulov\HyperParser
 */
interface DOMParserInterface
{
    /**
     * @param $string
     * @return DOMInterface
     */
    public function getDOM($string);
}
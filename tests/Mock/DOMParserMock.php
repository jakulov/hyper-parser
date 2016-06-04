<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 12:41
 */

/**
 * Class DOMParserMock
 */
class DOMParserMock implements \jakulov\HyperParser\DOMParserInterface
{
    /**
     * @param $string
     * @return DOMMock
     */
    public function getDOM($string)
    {
        return new DOMMock();
    }
}
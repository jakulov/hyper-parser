<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 12:42
 */

/**
 * Class DOMMock
 */
class DOMMock implements \jakulov\HyperParser\DOMInterface
{
    /**
     * @param $element
     * @return $this
     */
    public function find($element)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function outertext()
    {
        return '';
    }

    /**
     * @return string
     */
    public function innertext()
    {
        return '';
    }

    /**
     * @return string
     */
    public function text()
    {
        return '';
    }

    /**
     * @param $name
     * @return string
     */
    public function getAttribute($name)
    {
        return '';
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 0:00
 */

namespace jakulov\HyperParser;

/**
 * Interface DOMInterface
 * @package jakulov\HyperParser
 */
interface DOMInterface
{
    /**
     * @param $element
     * @return $this[]
     */
    public function find($element);

    /**
     * @return string
     */
    public function outertext();

    /**
     * @return string
     */
    public function innertext();

    /**
     * @return string
     */
    public function text();

    /**
     * @param $name
     * @return string
     */
    public function getAttribute($name);


}
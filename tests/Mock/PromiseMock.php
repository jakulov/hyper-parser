<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 12:48
 */

/**
 * Class PromiseMock
 */
class PromiseMock implements \Prophecy\Promise\PromiseInterface
{
    /**
     * @param array $args
     * @param \Prophecy\Prophecy\ObjectProphecy $object
     * @param \Prophecy\Prophecy\MethodProphecy $method
     * @return void
     */
    public function execute(array $args, \Prophecy\Prophecy\ObjectProphecy $object, \Prophecy\Prophecy\MethodProphecy $method)
    {
        // TODO: testing promise

        return;
    }

}
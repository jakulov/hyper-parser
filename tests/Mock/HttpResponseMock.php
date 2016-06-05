<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 12:46
 */

/**
 * Class HttpResponseMock
 */
class HttpResponseMock implements \Psr\Http\Message\ResponseInterface
{
    use HttpResponseTrait;
}
<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 12:45
 */
class HttpClientMock implements \GuzzleHttp\ClientInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array $options
     * @return HttpResponseMock
     */
    public function send(\Psr\Http\Message\RequestInterface $request, array $options = [])
    {
        return new HttpResponseMock();
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array $options
     * @return PromiseMock
     */
    public function sendAsync(\Psr\Http\Message\RequestInterface $request, array $options = [])
    {
       return new PromiseMock();
    }

    /**
     * @param string $method
     * @param null $uri
     * @param array $options
     * @return HttpResponseMock
     */
    public function request($method, $uri = null, array $options = [])
    {
        return new HttpResponseMock();
    }

    /**
     * @param string $method
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param array $options
     * @return PromiseMock
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        return new PromiseMock();
    }

    /**
     * @param null $option
     * @return array
     */
    public function getConfig($option = null)
    {
        return [];
    }

}
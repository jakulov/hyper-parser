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
    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return '';
    }

    /**
     * @param string $version
     * @return $this
     */
    public function withProtocolVersion($version)
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name)
    {
        return false;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getHeader($name)
    {
        return [];
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeaderLine($name)
    {
        return '';
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function withHeader($name, $value)
    {
        return $this;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function withAddedHeader($name, $value)
    {
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withoutHeader($name)
    {
        return $this;
    }

    /**
     * @return SteamMock
     */
    public function getBody()
    {
        return new SteamMock();
    }

    /**
     * @param \Psr\Http\Message\StreamInterface $body
     * @return $this
     */
    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return 200;
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return $this
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return '';
    }

}
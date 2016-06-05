<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 05.06.16
 * Time: 15:15
 */
trait HttpResponseTrait
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
     * @return StreamMock
     */
    public function getBody()
    {
        return new StreamMock();
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
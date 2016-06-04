<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 12:51
 */

/**
 * Class SteamMock
 */
class StreamMock implements \Psr\Http\Message\StreamInterface
{
    public function __toString()
    {
        return '';
    }

    public function close()
    {
        return;
    }

    public function detach()
    {
        return null;
    }

    public function getSize()
    {
        return 0;
    }

    public function tell()
    {
        return 0;
    }

    public function eof()
    {
        return true;
    }

    public function isSeekable()
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        return null;
    }

    public function rewind()
    {
        return;
    }

    public function isWritable()
    {
        return false;
    }

    public function write($string)
    {
        return;
    }

    public function isReadable()
    {
        return true;
    }

    public function read($length)
    {
        return '';
    }

    public function getContents()
    {
        return '';
    }

    public function getMetadata($key = null)
    {
        return [];
    }

}
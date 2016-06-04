<?php

/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 04.06.16
 * Time: 12:39
 */
class ParserTest extends PHPUnit_Framework_TestCase
{

    public function testParseUrl()
    {
        $parser = $this->getMockBuilder(\jakulov\HyperParser\Parser::class)
            ->setMethods(['fetchUrl', 'isResponseCanBeParsed', 'extractDataByPattern'])
            ->getMock();

        $url = 'http://localhost';
        $pattern = ['title' => 'title', 'content' => '.content|innertext'];
        $data = ['title' => 'Test Title', 'content' => 'Test Content'];
        $response = new HttpResponseMock();
        $parser
            ->expects($this->at(0))
            ->method('fetchUrl')
            ->with($this->equalTo($url))
            ->will($this->returnValue($response));

        $parser
            ->expects($this->at(1))
            ->method('isResponseCanBeParsed')
            ->with($this->equalTo($response))
            ->will($this->returnValue(true));

        $parser
            ->expects($this->at(2))
            ->method('extractDataByPattern')
            ->will($this->returnValue($data));

        if($parser instanceof \jakulov\HyperParser\Parser) {
            $actual = $parser->parseUrl($url, $pattern);

            $this->assertEquals($data, $actual);
        }
    }
}

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

    public function testFetchUrl()
    {
        $httpClientMock = $this
            ->getMockBuilder(HttpClientMock::class)
            ->setMethods(['request'])
            ->getMock();

        $method = 'GET';
        $url = 'http://localhost';
        $options = [];
        $content = '';
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with($this->equalTo($method), $this->equalTo($url), $this->equalTo($options))
            ->will($this->returnValue($content));

        if($httpClientMock instanceof \GuzzleHttp\ClientInterface) {
            $parser = new \jakulov\HyperParser\Parser();
            $actual = $parser->setHttpClient($httpClientMock)->fetchUrl($url, $method, $options);

            $this->assertEquals($content, $actual);
        }
    }

    public function testIsResponseCanBeParsed()
    {
        $parser = new \jakulov\HyperParser\Parser();

        $responseMock = $this->getMockBuilder(HttpResponseMock::class)->setMethods(['getStatusCode'])->getMock();
        $codeOk = 200;
        $codeFail = 404;
        $responseMock->expects($this->at(0))->method('getStatusCode')->will($this->returnValue($codeOk));
        $responseMock->expects($this->at(1))->method('getStatusCode')->will($this->returnValue($codeFail));
        $responseMock->expects($this->at(2))->method('getStatusCode')->will($this->returnValue($codeFail));

        if($responseMock instanceof \Psr\Http\Message\ResponseInterface) {
            $actual = $parser->isResponseCanBeParsed($responseMock);
            $this->assertEquals($actual, true);

            $actual = $parser->isResponseCanBeParsed($responseMock);
            $this->assertEquals($actual, false);

            $parser->ignoreHttpStatusCode = true;
            $actual = $parser->isResponseCanBeParsed($responseMock);
            $this->assertEquals($actual, true);
        }
    }

    public function testAddDOMParserClass()
    {
        $parser = new \jakulov\HyperParser\Parser();

        $alias = 'test';
        $class = '\DOMParserMock';

        $parser->addDOMParserClass($alias, $class)->setUseDOMParser($alias);
        $actual = $parser->getDOMParser();

        $this->assertEquals($actual, new $class);
    }

    public function testSetUseDOMParser()
    {
        $parser = new \jakulov\HyperParser\Parser();
        $parser->setUseDOMParser('test');

        $this->expectException(\jakulov\HyperParser\HyperParserException::class);
        $parser->getDOMParser();
    }

    public function testGetDOMParser()
    {
        $parser = new \jakulov\HyperParser\Parser();

        $this->assertInstanceOf(
            \jakulov\HyperParser\Bridge\SunraDOMParserBridge::class, $parser->getDOMParser()
        );
    }

    public function testSetDOMParser()
    {
        $parser = new \jakulov\HyperParser\Parser();
        $test = new DOMParserMock();
        $actual = $parser->setDOMParser($test)->getDOMParser();

        $this->assertEquals($test, $actual);
    }

    public function testGetHttpClient()
    {
        $parser = new \jakulov\HyperParser\Parser();

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $parser->getHttpClient());
    }

    public function testSetHttpClient()
    {
        $parser = new \jakulov\HyperParser\Parser();
        $test = new HttpClientMock();
        $actual = $parser->setHttpClient($test)->getHttpClient();

        $this->assertEquals($test, $actual);
    }

    public function testExtractDataByPattern()
    {
        // TODO: write test extract
    }
}

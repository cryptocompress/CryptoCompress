<?php

use \CryptoCompress\Http\Curl\Connection,
    \CryptoCompress\Http\Curl\Response,
    \CryptoCompress\Http\Curl\Request\Get,
    \CryptoCompress\Http\Curl\Request\Post;

class ConnectionTest extends \PHPUnit_Framework_TestCase {

    protected $url = 'http://www.google.com/';

    /**
     * @expectedException   CryptoCompress\Http\Curl\Exception
     */
    public function testReceiveBeforeRequest() {
        $connection = new Connection();

        $response = $connection->receive();

        $this->assertEquals('CryptoCompress\Http\Curl\Response', get_class($response));
        $this->assertEquals(200, $response->code());
    }

    public function testUno() {
        $connection = new Connection();

        $response = $connection->request(
            new Get($this->url)
        )->receive();

        $this->assertEquals('CryptoCompress\Http\Curl\Response', get_class($response));
        $this->assertEquals(200, $response->code());
    }

    public function testDuo() {
        $connection = new Connection();

        $response = $connection->fetch(
            new Get($this->url)
        );

        $this->assertEquals('CryptoCompress\Http\Curl\Response', get_class($response));
        $this->assertEquals(200, $response->code());
    }

    public function testHtml() {
        $connection = new Connection();

        $response = $connection->fetch(
            new Get($this->url)
        );

        $this->assertGreaterThan(0, $response->document()->getElementsByTagName('input')->length);
        $this->assertEquals('Google', $response->document()->getElementsByTagName('title')->item(0)->textContent);
    }
}
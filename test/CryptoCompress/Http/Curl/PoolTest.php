<?php

use \CryptoCompress\Http\Curl\Pool,
    \CryptoCompress\Http\Curl\Response,
    \CryptoCompress\Http\Curl\Request\Get,
    \CryptoCompress\Http\Curl\Request\Post;

class PoolTest extends \PHPUnit_Framework_TestCase {

    protected $url = 'http://www.google.com/';

    /**
     * @expectedException    CryptoCompress\Http\Curl\Exception
     */
    public function testReceiveBeforeRequest() {
        $pool = new Pool();

        $arr = $pool->receive();

        $this->assertEquals(0, count($arr));
    }

    public function testUno() {
        $pool = new Pool();

        $arr = $pool->request(
            array(new Get($this->url))
        )->receive();

        $response = current($arr);

        $this->assertEquals('CryptoCompress\Http\Curl\Response', get_class($response));
        $this->assertEquals(200, $response->code());
    }

    public function testDuo() {
        $pool = new Pool();

        $response = $pool->fetch(
            new Get($this->url)
        );

        $this->assertEquals('CryptoCompress\Http\Curl\Response', get_class($response));
        $this->assertEquals(200, $response->code());
    }

    public function testWithoutArray() {
        $pool = new Pool();

        $arr = $pool->fetchMany(
            array(new Get($this->url), new Get($this->url))
        );

        $this->assertEquals(2, count($arr));

        $response = current($arr);

        $this->assertEquals('CryptoCompress\Http\Curl\Response', get_class($response));
        $this->assertEquals(200, $response->code());
    }
}
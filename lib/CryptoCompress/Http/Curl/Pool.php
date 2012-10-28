<?php

namespace CryptoCompress\Http\Curl;

use \CryptoCompress\Http\IRequest;

class Pool implements \CryptoCompress\Http\ITransport {
#    use \CryptoCompress\Http\TMethods;

    public function get($url, array $get = array(), array $options = array()) {
    	return $this->fetch(new \CryptoCompress\Http\Curl\Request\Get($url, $get, $options));
    }

    public function post($url, array $get = array(), array $post = array(), array $options = array()) {
    	return $this->fetch(new \CryptoCompress\Http\Curl\Request\Post($url, $get, $post, $options));
    }

    public function getDocument($url, array $get = array(), array $options = array()) {
    	return $this->get($url, $get, $options)->document();
    }

    public function postDocument($url, array $get = array(), array $post = array(), array $options = array()) {
    	return $this->post($url, $get, $post, $options)->document();
    }

    /**
     * @var resource
     */
    private $handle;

    /**
     * @var array
     */
    protected $connections;

    public function __destruct() {
        if (is_resource($this->handle)) {
            curl_multi_close($this->handle);
        }
    }

    public function request(array $requests) {
        if (null == $this->handle) {
            $this->handle = curl_multi_init();
        }

        foreach ($requests as $key => $request) {
            $this->connections[$key] = new Connection();
            $this->connections[$key]->request($request, $this->handle);
        }

        return $this;
    }

    public function receive() {
        if (null == $this->handle) {
            throw new Exception('No request set!');
        }

        // exec
        $stillRunning = false;
        do {
            usleep(10000);
            $ret = curl_multi_exec($this->handle, $stillRunning);
        } while ($ret == CURLM_OK && $stillRunning);

        // receive
        $results = array();

        foreach ($this->connections as $key => $connection) {
            $results[$key] = $connection->receive($this->handle);
        }

        return $results;
    }

    public function fetch(IRequest $request) {
        return current($this->request(array($request))->receive());
    }

    public function fetchMany(array $requests) {
        return $this->request($requests)->receive();
    }
}
<?php

namespace CryptoCompress\Http\Curl;

class Request implements IRequest {

    protected $method;
    protected $url;
    protected $get;
    protected $post;
    protected $options;

    public function __construct($method, $url, array $get = array(), array $post = array(), array $options = array()) {
        $this->method   = $method;
        $this->url      = $url;
        $this->get      = $get;
        $this->post     = $post;

        if (empty($options)) {
            $openBaseDir = ini_get('open_basedir');
            if (empty($openBaseDir)) {
                $options[CURLOPT_FOLLOWLOCATION] = true;
            }

            $options[CURLOPT_MAXREDIRS]			= 2;
            $options[CURLOPT_TIMEOUT]			= 2;
            $options[CURLOPT_CONNECTTIMEOUT]	= 2;
        }

        $this->options  = $options;
    }

    public function __destruct() {
    }

    public function options() {
        $options = $this->options;

        if ($this->method == 'POST') {
            $options[CURLOPT_POST]			= true;
            $options[CURLOPT_POSTFIELDS]    = http_build_query($this->post);
        }

        $options[CURLOPT_URL]               = $this->prepareUrl($this->url, $this->get);
        $options[CURLOPT_RETURNTRANSFER]    = true;
        $options[CURLOPT_HEADER]            = true;

        return $options;
    }

    protected function prepareUrl($url, array $get = array()) {
        if (!empty($get)) {
            $url .= '?' . http_build_query($get);
        }

        return $url;
    }
}

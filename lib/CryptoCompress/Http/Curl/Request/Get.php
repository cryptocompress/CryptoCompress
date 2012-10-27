<?php

namespace CryptoCompress\Http\Curl\Request;

use CryptoCompress\Http\Curl\Request;

class Get extends Request {
    public function __construct($url, array $get = array(), array $options = array()) {
        parent::__construct('GET', $url, $get, array(), $options);
    }
}
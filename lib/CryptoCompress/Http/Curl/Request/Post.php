<?php

namespace CryptoCompress\Http\Curl\Request;

use CryptoCompress\Http\Curl\Request;

class Post extends Request {
    public function __construct($url, array $get = array(), array $post = array(), array $options = array()) {
        parent::__construct('POST', $url, $get, $post, $options);
    }
}
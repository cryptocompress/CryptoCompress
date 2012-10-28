<?php

namespace CryptoCompress\Http;

trait TMethods {
    public function get($url, array $get = array(), array $options = array()) {
    	return $this->fetch(new Curl\Request\Get($url, $get, $options));
    }

    public function post($url, array $get = array(), array $post = array(), array $options = array()) {
    	return $this->fetch(new Curl\Request\Post($url, $get, $post, $options));
    }

    public function getDocument($url, array $get = array(), array $options = array()) {
    	return $this->get($url, $get, $options)->document();
    }

    public function postDocument($url, array $get = array(), array $post = array(), array $options = array()) {
    	return $this->post($url, $get, $post, $options)->document();
    }
}
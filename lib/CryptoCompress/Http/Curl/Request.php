<?php

namespace CryptoCompress\Http\Curl;

use \CryptoCompress\Http\IRequest;

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

            $options[CURLOPT_MAXREDIRS]         = 2;
            $options[CURLOPT_TIMEOUT]           = 2;
            $options[CURLOPT_CONNECTTIMEOUT]    = 2;
        }

        $this->options  = $options;
    }

    public function options() {
        $options = $this->oauth($this->prepareUrl($this->url, $this->get), $this->options, $this->method, $this->get, $this->post);

        if ($this->method == 'POST') {
            $options[CURLOPT_POST]          = true;
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

    protected function oauth($url, array $options = array(), $method = 'GET', array $get = array(), array $post = array(), $timestamp = null, $nonce = null) {
        if (!isset($options['oauth'])) {
            return $options;
        }

        $config = $options['oauth'];
        unset($options['oauth']);

        $params = array();
        $auth   = array();

        if (!isset($timestamp)) {
            $timestamp = time();
        }

        if (!isset($nonce)) {
            $nonce = md5(microtime());
        }

        $params['oauth_consumer_key']       = $config['consumer_key'];
        $params['oauth_signature_method']   = $config['signature_method'];
        $params['oauth_token']              = $config['access_token'];
        $params['oauth_version']            = $config['version'];
        $params['oauth_nonce']              = $nonce;
        $params['oauth_timestamp']          = $timestamp;

        $params = $params + $post + $get;

        // encode all key valies
        foreach ($params as $k => $v) {
            $auth[rawurlencode($k)] = rawurlencode($v);
        }

        ksort($auth, SORT_STRING);

        $sig = '';
        foreach ($auth as $k => $v) {
            $sig .= $k . '=' . $v . '&';
        }

        $urlParts = parse_url($url);

        // encode & concat
        $signature  = strtoupper($method) . '&'
                    . rawurlencode($urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path']) . '&'
                    . rawurlencode(rtrim($sig, '&'));

        $signingKey = rawurlencode($config['consumer_secret']) . '&'
                    . rawurlencode($config['access_token_secret']);

        $header = 'OAuth '
                . 'oauth_consumer_key="'        . rawurlencode($config['consumer_key']) . '", '
                . 'oauth_nonce="'               . rawurlencode($nonce) . '", '
                . 'oauth_signature="'           . rawurlencode(base64_encode(hash_hmac('sha1', $signature, $signingKey, true))) . '", '
                . 'oauth_signature_method="'    . rawurlencode($config['signature_method']) . '", '
                . 'oauth_timestamp="'           . rawurlencode($timestamp) . '", '
                . 'oauth_token="'               . rawurlencode($config['access_token']) . '", '
                . 'oauth_version="'             . rawurlencode($config['version']) . '" ';

        if (!isset($options[CURLOPT_HTTPHEADER])) {
            $options[CURLOPT_HTTPHEADER] = array();
        }

        $options[CURLOPT_HTTPHEADER] += array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: ' . $header
        );

        return $options;
    }
}
<?php

namespace CryptoCompress\Http\Curl;

class Response {

    protected $url;
    protected $code;
    protected $message;
    protected $headers;
    protected $body;

    public function __construct($url, $code, $message, $headers, $body) {
        $this->code     = $code;
        $this->message  = $message;
        $this->url      = $url;
        $this->headers  = $headers;
        $this->body     = $body;
    }

    public function code() {
        return $this->code;
    }

    public function header($name) {
        return @$this->headers[$name];
    }

    public function __toString() {
        return $this->body;
    }

    public function document() {
        $mimeType   = 'text/html';  // rfc2616 sec 7.2.1 => should be 'application/octet-stream' by default but we may guess :)
        $charSet    = 'ISO-8859-1'; // rfc2616 sec 3.7.1 => should be 'ISO-8859-1' by default

        // parse mime-type and charset
        if (preg_match('/^\s*([^\/]*\/[^;]*)\s*;{0,1}\s*.*?charset\=([^\s]*).*?$/', strtolower($this->header('content-type')), $m)) {
            $mimeType = $m[1];
            if (isset($m[2])) {
                $charSet = $m[2];
            }
        }

        return $this->map($mimeType, $charSet, $this->body);
    }

    /**
     * @return    mixed
     */
    protected function map($mimeType, $charSet, $message) {
        $message = iconv($charSet, 'UTF-8', $message);

        switch ($mimeType) {
            case 'application/json':
                return $this->json($message);

            case 'text/html':
                return $this->dom($message, 'html');

            case 'application/atom+xml':
            case 'application/rss+xml':
            case 'application/xml':
            case 'text/xml':
                return $this->dom($message, 'xml');
        }

        throw new Exception('No document-mapping for mime-type [' . $mimeType . '].');
    }

    /**
     * @return    array
     */
    protected function json($message) {
        return json_decode($message, true);
    }

    /**
     * @return    \DOMDocument
     */
    protected function dom($message, $type = 'XML') {
        // TODO: CHECK IF THIS IS NEEDED AS WE DO NOT VALIDATE HERE
        $internalErrors     = libxml_use_internal_errors(true);
        $disableEntities    = libxml_disable_entity_loader(true);
        libxml_clear_errors();

        // FIXME: parse VERSION from document?
        $version    = '1.0';
        $encoding   = 'UTF-8';
        $document   = new \DOMDocument($version, $encoding);

        $document->recover              = true;
        $document->validateOnParse      = false;
        $document->strictErrorChecking  = true;
        $document->preserveWhiteSpace   = false;
        #$document->substituteEntities   = true; // do not substitute (external!!!) entities automaticaly
        $document->formatOutput         = true;

        $document->{'load' . strtoupper($type)}($message);

        // TODO: CHECK IF THIS IS NEEDED AS WE DO NOT VALIDATE HERE
        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);
//        if ($error = libxml_get_last_error()) {
//            throw new Exception($error->message);
//        }

        return $document;
    }
}

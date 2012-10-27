<?php

namespace CryptoCompress\Http\Curl;

use \CryptoCompress\Http\IRequest;

class Connection implements \CryptoCompress\Http\ITransport {

    /**
     * @var resource
     */
    protected $handle;

    public function __destruct() {
        if (is_resource($this->handle)) {
            curl_close($this->handle);
        }
    }

    public function __clone() {
        $this->handle = curl_copy_handle($this->handle);
    }

    public function request(IRequest $request, $multiHandle = null) {
        if (null == $this->handle) {
            $this->handle = curl_init();
        }

        if (is_resource($multiHandle)) {
            $returnCode = curl_multi_add_handle($multiHandle, $this->handle);
            if ($returnCode != CURLM_OK) {
                throw new Exception('[' . curl_errno($this->handle) . '] ' . curl_error($this->handle), $returnCode);
            }
        }

        foreach ($request->options() as $key => $value) {
            if (!curl_setopt($this->handle, $key, $value)) {
                throw new Exception(curl_error($this->handle), curl_errno($this->handle));
            }
        }

        return $this;
    }

    public function receive($multiHandle = null) {
        if (null == $this->handle) {
            throw new Exception('No request set!');
        }

        if (is_resource($multiHandle)) {
            $response = curl_multi_getcontent($this->handle);
            curl_multi_remove_handle($multiHandle, $this->handle);
        } else {
            $response = curl_exec($this->handle);
        }

        if ($response === false || strlen($response) < 1) {
            throw new Exception('Response was empty!' . "\n" . curl_error($this->handle), curl_errno($this->handle));
        }

        return $this->parse($response);
    }

    public function fetch(IRequest $request) {
        return $this->request($request)->receive();
    }

    public function fetchMany(array $requests) {
    	$responses = array();

    	foreach ($requests as $key => $request) {
    		$responses[$key] = $this->request($request)->receive();
    	}

        return $responses;
    }

	####################
	## HELPER METHODS ##
	####################

    public function parse($response) {
        $url    = curl_getinfo($this->handle, CURLINFO_EFFECTIVE_URL);
        $code   = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
        $length = curl_getinfo($this->handle, CURLINFO_HEADER_SIZE);

        $header = substr($response, 0, $length);
        $body   = substr($response, $length);

        list ($message, $headers) = $this->parseHeader($header);

        if (substr($code, 0, 1) != 2 && empty($body)) {
            throw new Exception($message, $code);
        }

        return new Response($url, $code, $message, $headers, $body);
    }

    protected function parseHeader($strHeaders) {
        $message    = '';
        $headers    = array();

        $arrBlocks  = explode("\r\n\r\n", trim($strHeaders));
        $block      = array_pop($arrBlocks); // throw away previous header blocks from redirects
        foreach (explode("\n", $block) as $line) { // RFC: \r\n
            $keyValue = explode(':', $line, 2);
            if (count($keyValue) == 2) {
                $headers[strtolower(trim($keyValue[0]))] = trim($keyValue[1]);
            } else if (count($keyValue) == 1 && preg_match('/HTTP\/\d+\.\d+\s+\d{3}\s+(.*)$/', $keyValue[0], $m)) {
                $message = $m[1];
            }
        }

        return array($message, $headers);
    }
}
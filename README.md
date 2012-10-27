# CryptoCompress Library

[![Build Status](https://secure.travis-ci.org/cryptocompress/CryptoCompress.png?branch=master)](http://travis-ci.org/cryptocompress/CryptoCompress)

## Http Curl Connection Usage
    $connection = new Connection();
    $response   = $connection->fetch(new Get('http://www.google.com/'));

## Http Curl Pool Usage
    $pool       = new Pool();
    $responses  = $pool->fetch(array(new Get('http://www.google.com/')));
    
## Http Curl Response Usage
    $connection = new Connection();
    $response   = $connection->fetch(new Get('http://www.google.com/'));
    $google     = $response->document()->getElementsByTagName('title')->item(0)->textContent;
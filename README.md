# CryptoCompress Library

[![Build Status](https://secure.travis-ci.org/cryptocompress/CryptoCompress.png?branch=master)](http://travis-ci.org/cryptocompress/CryptoCompress)

### Http Curl

#### single request
    $connection = new Connection();
    $response   = $connection->fetch(new Get('http://www.google.com/'));

#### multi request
    $pool       = new Pool();
    $responses  = $pool->fetchMany(array(new Get('http://www.google.com/'), new Get('http://www.google.de/')));
    
#### response usage
    $connection = new Connection();
    $response   = $connection->fetch(new Get('http://www.google.com/'));
    echo $response->document()->getElementsByTagName('title')->item(0)->textContent;
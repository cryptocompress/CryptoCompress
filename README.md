# CryptoCompress Library

[![Build Status](https://secure.travis-ci.org/cryptocompress/CryptoCompress.png?branch=master)](http://travis-ci.org/cryptocompress/CryptoCompress)

### Install

    mkdir my-test-project
    cd my-test-project
    curl -s http://getcomposer.org/installer | php
    php composer.phar init -n && php composer.phar require cryptocompress/cryptocompress:dev-master
    
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

### Try out and play around

#### Get my latest tweet
``vi test.php && php test.php``

    <?php require __DIR__ . '/vendor/autoload.php';
    use \CryptoCompress\Http\Curl\Connection, \CryptoCompress\Http\Curl\Request\Get;
    $connection = new Connection();
    echo $connection->fetch(new Get('http://twitter.com/CryptoCompress'))
                    ->document()
                    ->getElementsByTagName('p')->item(3)->textContent;
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

#### Get current weather in munich
``vi weather.php && php weather.php``

    <?php require __DIR__ . '/vendor/autoload.php';
        $weather = new Weather(array('url' => 'http://weather.yahooapis.com/forecastrss', 'degree' => Weather::DEGREE_CELSIUS), new Pool());
        var_dump($weather->getByCode(Weather::CODE_MUNICH)->current());

#### Get my latest tweet
``vi tweet.php && php tweet.php``

    <?php require __DIR__ . '/vendor/autoload.php';
    $con = new \CryptoCompress\Http\Curl\Connection()
    echo $con->getDocument('http://twitter.com/CryptoCompress')
             ->getElementsByTagName('p')->item(3)->textContent;
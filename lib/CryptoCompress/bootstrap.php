<?php

namespace CryptoCompress;

ini_set('display_errors',    false);
ini_set('error_reporting',    -1);

set_error_handler(function ($code, $message, $file = null, $line = 0) {
    if (error_reporting() == 0) {
        return true;
    }

    throw new \ErrorException($message, $code, null, $file, $line);
});

spl_autoload_register(function ($class) {
    @include __DIR__ . '/../' . $class . '.php';
}, true, true);

if (!defined('CRYPTOCOMPRESS_PATH')) {
    define('CRYPTOCOMPRESS_PATH', realpath(__DIR__ . '/../'));
}
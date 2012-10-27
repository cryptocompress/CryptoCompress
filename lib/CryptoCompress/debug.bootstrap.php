<?php

namespace CryptoCompress;

require_once __DIR__ . '/bootstrap.php';

ini_set('display_errors', true);

if (!function_exists('w')) {
    require_once __DIR__ . '/Debug.php';
}

register_shutdown_function(function () {
    $displayErrors = ini_get('display_errors');
    $e = error_get_last();
    if ($displayErrors && null != $e) {
        die('Error in file ' . $e['file'] . ' line ' . $e['line'] . ":\n" . $e['message']);
    }
});
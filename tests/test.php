<?php

use Simplon\Http\Adapter\GuzzleHttp;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/SomeHttp.php';

try
{
    $foo = new SomeHttp(new GuzzleHttp(), require __DIR__ . '/config.php');
    var_dump($foo->register());
}
catch (\Http\Client\Exception | Exception $e)
{
    var_dump($e->getMessage());
}
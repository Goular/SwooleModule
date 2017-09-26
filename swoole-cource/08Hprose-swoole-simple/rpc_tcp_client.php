<?php
require_once "vendor/autoload.php";

use Hprose\Swoole\Client;

$client = new Client('tcp://127.0.0.1:7071');
$client->upload(25,28)->then(function($result) {
    echo $result;
}, function($e) {
    echo $e;
});

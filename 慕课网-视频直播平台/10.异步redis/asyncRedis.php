<?php

$redisClient = new swoole_redis();
$redisClient->connect("127.0.0.1", 6379, function ($redisClient, $result) {
    echo "Connect " . PHP_EOL;
    var_dump($result);

    // 下面的例子，都是异步场景

    // 设定参数
//    $redisClient->set('singwa_1', time(), function (swoole_redis $redisClient, $result) {
//        var_dump($result);
//    });

    // 获取参数
//    $redisClient->get('singwa_1-1', function (swoole_redis $redisClient, $result) {
//        var_dump($result);
//        $redisClient->close();
//    });

    $redisClient->keys('*gw*', function (swoole_redis $redisClient, $result) {
        var_dump($result);
        $redisClient->close();
    });

});
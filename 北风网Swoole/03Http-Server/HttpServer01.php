<?php
$serv = new swoole_http_server('0.0.0.0', 7070);
//获取请求
//1.$request 请求消息 GET/POST
//2.$response 返回信息
$serv->on('request', function ($request, $response) {
    var_dump($request);
    $response->header("Content-Type", "text/html;charset=utf-8");//设置返回头信息
    $response->end("hello world!" . rand(100, 999));
});
$serv->start();
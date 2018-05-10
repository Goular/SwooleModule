<?php

$http = new swoole_http_server("0.0.0.0", 7071);
// 静态资源选择，如果获取的是静态资源那么我们就不会进行request回调，
$http->set([
    'enable_static_handler' => true,
    'document_root'=>"/home/root/swoole"
]);
$http->on("request", function ($request, $response) {
    $response->cookie("goular", "xssss", time() + 1800);
    $response->end("<h1>Http Server！</h1><pre>" . json_encode($request->get) . "</pre>");
});
$http->start();
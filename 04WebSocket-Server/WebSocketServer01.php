<?php
//创建WebSocket服务器
$ws = new swoole_websocket_server("0.0.0.0", 7070);
//open建立连接 $ws服务器 $request 客户端信息
$ws->on('opne', function (swoole_websocket_server $ws, $request) {
    var_dump($request);
    $ws->push($request->fd, "Welcome \n");
});
//message 接收信息
$ws->on("message", function (swoole_websocket_server $ws, $request) {
    echo "Message::" . $request->data;
    $ws->push($request->fd, "get it message");
});
//close 关闭连接
$ws->on("close", function ($ws, $request) {
    echo "close\n";
});
//开启服务器
$ws->start();

<?php

$server = new swoole_websocket_server("", 7071);

// 服务器配置
// $server->set([]);

// 监听WebSocket连接打开事件
$server->on('open', 'onOpen');


// 这里一样可以添加直接读取静态资源
//$http->set([
//    'enable_static_handler' => true,
//    'document_root'=>"/home/root/swoole"
//]);

function onOpen(swoole_websocket_server $server, $request)
{
    print_r($request->fd);
//    echo "server: handshake success with fd{$request->fd}\n";
}

// 监听WS消息事件
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "Goular push success");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();
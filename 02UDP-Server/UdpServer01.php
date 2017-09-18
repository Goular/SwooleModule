<?php
$serv = new swoole_server("0.0.0.0", 7070, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
//监听数据接收的事件
/**
 * $serv : 服务器信息
 * $data : 数据，接收到的信息
 * $fd : 客户端信息
 */
$serv->on('packet', function ($serv, $data, $fd) {
    //发送数据到相应的客户端，反馈消息
    //$serv->sendto($fd['address'], $fd['port'], "Server:" + $data);
    var_dump($serv);
    var_dump($fd);
    var_dump($data);
});
$serv->start();
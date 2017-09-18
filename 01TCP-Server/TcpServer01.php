<?php
//定义相关的配置属性
$host = "0.0.0.0";//主机地址,字段类型:String
$post = 7070;//端口号,字段类型:Integer，端口可以自定义

//创建服务器(四个参数，目前只写了两个，其他都是使用默认配置)
/**
 * $host 127.0.0.1 默认监听本地的IP
 *       192.168.3.125 监听对应提供外网服务的IP
 *       0.0.0.0 表示监听所有地址
 * $port 端口号,监听小于1024端口需要root权限,如果此端口被占用server->start时会失败
 * $mode 运行的模式，swoole提供了3种运行模式，默认为SWOOLE_PROCESS多进程模式
 * $sock_type 指定Socket的类型,默认SWOOLE_SOCK_TCP
 */
$serv = new swoole_server($host, $post);
//使用回调
//bool $swoole_server->on(string $event,mixed $callback);
/**
 * TCP服务存在的$event
 * connect 当建立连接时，$serv:服务器信息  $fd:客户端信息
 * receive 当接收到数据，$serv：服务器信息，$fd:客户端信息，$from_id：用户ID，$data:数据
 * close 关闭连接，$serv:服务器信息  $fd:客户端信息
 */
$serv->on('connect', function ($serv, $fd) {
    echo "建立连接-start";
//    var_dump($serv);
    var_dump($fd);
    echo "建立连接-end";
});
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    echo "接收信息-start";
//    var_dump($serv);
    var_dump($fd);
    var_dump($from_id);
    var_dump($data);
    echo "接收信息-end";
});
$serv->on('close',function ($serv, $fd){
    echo "关闭连接-start";
//    var_dump($serv);
    var_dump($fd);
    echo "关闭连接-end";
});
//启动TCP服务器
$serv->start();

<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);
//连接到服务器
if (!$client->connect('127.0.0.1', 7070, 1)) {
    die("Connect failed!");
}
//向服务器发送数据
if ($client->send("Hello Sync Tcp Client")) {
    die("Send failed！");
}
//从服务器接收数据
$data = $client->recv();
if (!$data) {
    die("Receive failed！");
}
echo $data;
//关闭连接
$client->close();
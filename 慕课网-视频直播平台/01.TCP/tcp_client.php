<?php
// 连接swoole tcp服务
$client = new swoole_client(SWOOLE_SOCK_TCP);
// 如果连接失败
if (!$client->connect("127.0.0.1", 7070)) {
    echo "连接失败";
    exit;
}
// PHP Cli常量
fwrite(STDOUT, "请输入信息:");
$msg = trim(fgets(STDIN));

// 发送消息给TCP 服务器
$client->send($msg);

// 接收来自server的数据
$result = $client->recv();
echo $result;
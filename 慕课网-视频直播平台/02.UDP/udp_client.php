<?php
$client = new swoole_client(SWOOLE_SOCK_UDP);
if (!$client->connect('127.0.0.1', 7071, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}
// PHP Cli常量
fwrite(STDOUT, "请输入信息:");
$msg = trim(fgets(STDIN));
$client->send($msg);
echo $client->recv();
$client->close();
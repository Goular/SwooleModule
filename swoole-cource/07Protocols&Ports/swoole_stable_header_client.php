<?php

/**
 * 专门为EOF协议使用
 */
class Client
{
    private $client;

    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
    }

    public function connect()
    {
        if (!$this->client->connect("127.0.0.1", 7070)) {
            echo "Error:{$this->client->errMsg}[{$this->client->errCode}]\n";
        }
        $msg_normal = "This is a Msg.";
        $msg_length = pack("N", strlen($msg_normal)) . $msg_normal;
        echo "\n".$msg_length."\n";
        $i = 0;
        while ($i < 100) {
            $this->client->send($msg_length);
            $i++;
        }
    }
}

$client = new Client();
$client->connect();
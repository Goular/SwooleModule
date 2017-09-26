<?php

/**
 * 专门为
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
        $msg_eof = "This is a Msg" . random_int(100, 999) . "\r\n";
        $i = 0;
        while ($i < 100) {
            $this->client->send($msg_eof);
            $i++;
        }
    }
}

$client = new Client();
$client->connect();
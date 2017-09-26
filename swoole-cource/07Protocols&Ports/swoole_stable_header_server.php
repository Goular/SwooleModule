<?php

/**
 * 专门为EOF协议使用
 */
class server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 7070);
        $this->serv->set([
            'worker_num' => 1,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'package_max_length' => 8192,
            'open_length_check' => true,
            'package_length_offset' => 0,
            'package_body_offset' => 4,
            'package_length_type' => "N"
        ]);

        $this->serv->on("Start", array($this, "onStart"));
        $this->serv->on("Connect", array($this, "onConnect"));
        $this->serv->on("Receive", array($this, "onReceive"));
        $this->serv->on("Close", array($this, "onClose"));

        $this->serv->start();
    }

    public function onStart(swoole_server $serv)
    {
        echo "Start\n";
    }

    public function onConnect(swoole_server $serv, $fd, $from_id)
    {
        echo "Client {$fd} connect.\n";
    }

    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        //返回数据的长度
        $length = unpack("N", $data)[1];
        echo "Length={$length}\n";
        $msg = substr($data, -$length);
        echo "Get Message From Client {$fd}:{$msg}";
    }

    public function onClose(swoole_server $serv, $fd, $from_id)
    {
        echo "Client {$fd} Close Connection.\n";
    }
}

$server = new Server();
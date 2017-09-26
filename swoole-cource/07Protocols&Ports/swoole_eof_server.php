<?php

/**
 * 专门为
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
            'open_eof_check' => true,
            //'open_eof_split' => true,
            'package_eof' => "\r\n"
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
        var_dump($data);
        //这个是配置参数:open_eof_check专用，因为整个数据包是从后往前读，只要遇到分隔符就直接返回，
        //这样前面的分隔符就会被忽略同时全部返回，这样不好，所以我们需要在这里进行分隔，open_eof_split不会出现这个问题
        $data_list = explode("\r\n", $data);
        foreach ($data_list as $msg) {
            if (!empty($msg)) {
                echo "Get Message From Client {$fd}:{$msg}\n";
            }
        }
    }

    public function onClose(swoole_server $serv, $fd, $from_id)
    {
        echo "Client {$fd} Close Connection.\n";
    }
}

$server = new Server();
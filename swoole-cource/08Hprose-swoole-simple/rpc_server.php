<?php
require "vendor/autoload.php";

use Hprose\Swoole\Server as SwooleServer;
use Hprose\Swoole\Socket\Service;

/**
 * rpc测试使用
 */
class server
{
    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_http_server("0.0.0.0", 7070);
        $this->serv->set([
            'worker_num' => 1,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
        ]);

        $this->serv->on("Start", array($this, "onStart"));
        $this->serv->on("Connect", array($this, "onConnect"));
        $this->serv->on("Request", array($this, "onRequest"));
        $this->serv->on("Close", array($this, "onClose"));

        //添加 RPC port
        $port = $this->serv->listen("0.0.0.0", 7071, SWOOLE_SOCK_TCP);
        $port->set([
            "open_eof_split" => false
        ]);
        $rpc_service = new Service();
        $rpc_service->socketHandle($port);
        $rpc_service->addFunction([$this, 'upload']);
        $rpc_service->addFunction([$this, 'add']);

        //添加UDP 端口监听
        $udp_port = $this->serv->listen("0.0.0.0", 7072, SWOOLE_SOCK_UDP);
        $udp_port->on('packet', function ($serv, $data, $addr) {
            var_dump($data, $addr);
        });

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

    public function onRequest($request, $response)
    {
        var_dump($request->id);
    }

    public function upload($data)
    {
        var_dump($data);
        return "Server::" . $data;
    }

    public function add($a1, $a2)
    {
        return "Server2016::" . ($a1 + $a2);
    }

    public function onClose(swoole_server $serv, $fd, $from_id)
    {
        echo "Client {$fd} Close Connection.\n";
    }
}

$server = new Server();
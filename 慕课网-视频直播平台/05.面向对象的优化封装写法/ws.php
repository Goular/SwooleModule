<?php

class Ws
{

    CONST HOST = "0.0.0.0";
    CONST PORT = 7070;

    public $ws = null;

    public function __construct()
    {
        $this->ws = new swoole_websocket_server("0.0.0.0", 7070);
        $this->ws->on("open", [$this, "onOpen"]);
        $this->ws->on("message", [$this, "onMessage"]);
        $this->ws->on("close", [$this, "onClose"]);
        $this->ws->start();
    }

    // 监听ws连接事件
    public function onOpen($ws, $request)
    {
        var_dump($request->fd);
    }

    // 监听WS消息事件
    public function onMessage($ws, $frame)
    {
        echo "ser-push-message:{$frame->data}\n";
        $ws->push($frame->fd, "server-push:" . date("Y-m-d H:i:s"));
    }

    // 关闭
    public function onClose($ws, $fd)
    {
        echo "ClientID:{$fd}\n";
    }

}

$obj = new Ws();
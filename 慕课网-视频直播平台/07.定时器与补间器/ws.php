<?php

class Ws
{

    CONST HOST = "0.0.0.0";
    CONST PORT = 7070;

    public $ws = null;

    public function __construct()
    {
        $this->ws = new swoole_websocket_server("0.0.0.0", 7071);
        // 使用异步任务必须要设定task和worker的数量
        $this->ws->set([
            "worker_num" => 2,
            "task_worker_num" => 2
        ]);
        $this->ws->on("open", [$this, "onOpen"]);
        $this->ws->on("message", [$this, "onMessage"]);
        $this->ws->on("close", [$this, "onClose"]);
        $this->ws->on("task", [$this, "onTask"]);
        $this->ws->on("finish", [$this, "onFinish"]);
        $this->ws->start();
    }

    // 监听ws连接事件
    public function onOpen($ws, $request)
    {
        var_dump($request->fd);
        if ($request->fd == 1) {
            // 每两秒执行一次
            swoole_timer_tick(2000, function ($timer_id) {
                echo "2s:timerID:{$timer_id}\n";
            });
        }
    }

    // 监听WS消息事件
    public function onMessage($ws, $frame)
    {
        echo "ser-push-message:{$frame->data}\n";
        // todo: 有一个需要耗时10s的任务
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        $ws->task($data);
        // 5s后运行
        swoole_timer_after(500, function () use ($ws, $frame) {
            echo "5S - After\n";
            $ws->push($frame->fd, "Server - time - after :");
        });

        $ws->push($frame->fd, "server-push:" . date("Y-m-d H:i:s"));
    }

    public function onTask($serv, $task_id, $src_worker_id, $data)
    {
        print_r($data);
        // todo: 耗时场景
        sleep(10);
        // 将数据返回到worker
        return "On Task Finish";
    }

    // 任务完成后返回的内容
    public function onFinish($serv, $taskId, $data)
    {
        echo "taskID:{$taskId}\n";
        // 这个data为onTask return的内容
        echo "Finish-data-success:{$data}\n";
    }

    // 关闭
    public function onClose($ws, $fd)
    {
        echo "ClientID:{$fd}\n";
    }

}

$obj = new Ws();
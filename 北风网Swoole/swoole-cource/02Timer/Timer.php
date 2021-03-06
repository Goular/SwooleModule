<?php

class Test
{
    public $index = 0;
}

class Server
{
    private $serv;
    private $test;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 7070);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'task_worker_num' => 8
        ));
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        $this->serv->on('WorkerStart', array($this, 'onWorkStart'));

        $this->serv->start();
    }

    public function onWorkStart(swoole_server $serv, $worker_id)
    {
        if ($worker_id == 0) {

            //写法1:
//            swoole_timer_tick(1000, function ($timer_id, $params) {
//                echo "Timer running\n";
//                echo "recv:{$params}\n";
//            }, "Hello");
            //写法2：
            swoole_timer_tick(1000,array($this,'onTick'),"Hello222");
        }
    }

    public function onStart($serv)
    {
        echo "Start\n";
    }

    public function onConnect($serv, $fd, $from_id)
    {
        echo "Client {$fd} connect\n";
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }

    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        echo "Get Message From Client {$fd}:{$data}\n";

        swoole_timer_after(1000, function () use ($serv, $fd) {
            echo "Timer after\n";
            $serv->send($fd, "Hello later :" . time() . "\n");
        });

        echo "Continue Handle Worker\n";
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {
        echo "This Task {$task_id} from Worker {$from_id}\n";
        echo "Data:{$data}\n";
    }

    public function onFinish($serv, $task_id, $data)
    {
        echo "Task {$task_id} finish\n";
        echo "Result:{$data}\n";
    }

    public function onTick($timer_id, $params = null)
    {
        echo "Timer {$timer_id} running\n";
        echo "Params:{$params}\n";

        echo "Timer running\n";
        echo "recv：{$params}";

        var_dump($this->test);
    }
}

$server = new Server();
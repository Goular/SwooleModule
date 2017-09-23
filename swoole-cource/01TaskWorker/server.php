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
        ));
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));

        $this->serv->start();
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

    /**
     * @param swoole_server $serv
     * @param $fd       TCP客户端的标记
     * @param $from_id  reactor线程id
     * @param $data
     */
    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        echo "Get Message From Client {$fd}:{$data}\n";

        $data = [
            'task' => 'task_1',
            'params' => $data,
            'fd' => $fd
        ];

        $serv->task(json_encode($data));
    }


    public function onTask(swoole_server $serv, $task_id, $from_id, $data)
    {
        echo "This Task {$task_id} from Worker {$from_id}\n";
        echo "Data:{$data}\n";

        $data = json_decode($data,true);

        echo "Receive Task:{$data['task']}\n";
        var_dump($data['params']);

        $serv->send($data['fd'], "Hello Task");
        return "Finished.";
    }

    public function onFinish($serv, $task_id, $data)
    {
        echo "Task {$task_id} finish\n";
        echo "Result:{$data}\n";
    }

}

$server = new Server();
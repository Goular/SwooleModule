<?php

class MySQLPool
{
    private $serv;
    private $pdo;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 7070);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 3,
            "debug_mode" => 1,
            'task_worker_num' => 8
        ));

        $this->serv->on('WorkerStart', array($this, 'onWorkStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));

        $this->serv->start();
    }

    public function onConnect($serv, $fd, $from_id)
    {
        echo "Client {$fd} connect\n";
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }

    public function onStart($serv)
    {
        echo "Start\n";
    }

    public function onWorkStart(swoole_server $serv, $worker_id)
    {
        if ($serv->taskworker) {
            $this->pdo = new PDO("mysql:host=localhost;port=3306;dbname=test01", "goular", "3071611103", [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true
            ]);
            echo "Task Worker！\n";
        } else {
            echo "Worker Process！\n";
        }

    }

    /**
     * @param swoole_server $serv
     * @param $fd       TCP客户端的标记
     * @param $from_id  reactor线程id
     * @param $data
     */
    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        $task = [
            'sql' => "insert into t_user values(?,?);",
            'params' => [1, 'swoole'],
            'fd' => $fd
        ];
        $serv->task(json_encode($task));
    }


    public function onTask(swoole_server $serv, $task_id, $from_id, $data)
    {
        try {
            $data = json_decode($data, true);
            $statement = $this->pdo->prepare($data["sql"]);
            $statement->execute($data["params"]);
            $serv->send($data['fd'], "Data Insert Successful!");
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function onFinish($serv, $task_id, $data)
    {
        echo "result:{$data}\n";
    }

}

$mySQLPool = new MySQLPool();
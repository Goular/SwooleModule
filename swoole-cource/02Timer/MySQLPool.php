<?php

class MySQLPool
{
    private $serv;
    private $pdo;

    /**
     * MySQLPool constructor.
     */
    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 7070);
        $this->serv->set([
            "worker_num" => 8,
            "daemonize" => false,
            "max_request" => 10000,
            "dispatch_mode" => 1,
            "debug_mode" => 1,
            "task_worker_num" => 8
        ]);
        $this->serv->on('WorkerStart', array($this, 'onStart'));
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
    }


}
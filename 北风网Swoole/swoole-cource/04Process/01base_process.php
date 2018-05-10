<?php

/**
 * Created by PhpStorm.
 * User: zhaoj
 * Date: 2017-09-25
 * Time: 10:56
 */
class BaseProcess
{
    private $process;

    public function __construct()
    {
        //主线程下开出一条子线程
        $this->process = new swoole_process([$this, "run"], false, true);
        $this->process->daemon(true, true);
        $this->process->start();

        swoole_event_add($this->process->pipe, function ($pipe) {
            $data = $this->process->read();
            echo "Recv:" . $data . PHP_EOL;
        });
    }

    public function run($worker)
    {
        swoole_timer_tick(1000, function ($timer_id) {
            static $index = 0;
            $index += 1;
            $this->process->write("Hello");
            var_dump($index);
            if ($index == 10) {
                swoole_timer_clear($timer_id);
            }
        });
    }
}

$process = new BaseProcess();
swoole_process::signal(SIGCHLD, function ($sig) {
    while ($ret = swoole_process::wait(false)) {
        echo "PID={$ret['pid']}\n";
        var_dump($ret);
    };
});
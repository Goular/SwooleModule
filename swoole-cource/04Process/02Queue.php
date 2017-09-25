<?php

/**
 * Created by PhpStorm.
 * User: zhaoj
 * Date: 2017-09-25
 * Time: 13:12
 */
class BaseProcess
{
    private $process;

    /**
     * BaseProcess constructor.
     * @param $process
     */
    public function __construct()
    {
        $this->process = new swoole_process([$this, 'run'], false, true);
        //创建消息队列
        if (!$this->process->useQueue(123)) {
            var_dump(swoole_strerror(swoole_errno()));
            exit();
        }
        //开启子进程
        $this->process->start();
        //持久pop
        while (true) {
            $data = $this->process->pop();
            echo "Recv:", $data, PHP_EOL;
        }
    }

    public function run($worker)
    {
        swoole_timer_tick(1000, function ($timer_id) {
            static $index = 0;
            $index += 1;
            $this->process->push("Hello" . random_int(100, 999));
            var_dump($index);
            if ($index == 10) {
                swoole_timer_clear($timer_id);
            }
        });
    }
}

new BaseProcess();
swoole_process::signal(SIGCHLD, function ($sig) {
    //必须为false，非阻塞模式
    while ($ret = swoole_process::wait(false)) {
        echo "PID={$ret['pid']}", PHP_EOL;
    }
});
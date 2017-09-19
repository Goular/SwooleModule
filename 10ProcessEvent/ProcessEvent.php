<?php
//进程数组
$workers = [];
//创建进程的数据量
$workers_num = 3;
for ($i = 0; $i < $workers_num; $i++) {
    //创建单独新进程
    $process = new swoole_process('doProcess');
    //启动进程，并获得进程ID
    $pid = $process->start();
    //存入进程数组
    $workers[$pid] = $process;
}
//创建进程执行函数
function doProcess(swoole_process $process)
{
    //子进程写入信息 pipe
    $process->write("PID:" . $process->pid);
    echo "写入信息:$process->pid $process->callback";
}

//添加进程事件，向每一个子进程添加需要执行的动作
foreach ($workers as $process) {
    swoole_event_add($process->pipe, function ($pipe) use ($process) {
        $data = $process->read();//能否读取数据
        echo "接收到:$data\n";
    });
}
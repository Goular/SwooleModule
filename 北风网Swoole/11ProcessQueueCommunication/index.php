<?php
$workers = [];//进程仓库
$worker_num = 2;//最大进程数
//批量创建进程
for ($i = 0; $i < $worker_num; $i++) {
    $process = new swoole_process('doProcess', false, false);
    //开启队列，类似全局函数
    $process->useQueue();
    $pid = $process->start();
    $workers[$pid] = $process;
}
//进程执行函数
function doProcess(swoole_process $process)
{
    $recv = $process->pop();
    echo "从主进程获取到数据: $recv \n";
    sleep(5);
    $process->exit(0);
}

//主进程 向子进程添加数据
foreach ($workers as $pid => $process) {
    $process->push("Hello 子进程 $pid \n");
}

//等待子进程结束回收资源
for ($i = 0; $i < $worker_num; $i++) {
    $ret = swoole_process::wait();
    $pid = $ret['pid'];
    unset($workers[$pid]);
    echo "子进程退出:$pid \n";
}
<?php
$lock = new swoole_lock(SWOOLE_MUTEX);
echo "创建互斥锁\n";
$lock->lock();//当前的主进程进行锁定
if (pcntl_fork() > 0) {
    sleep(1);
    $lock->unlock();
} else {
    echo "子进程 等待锁\n";
    $lock->lock();//上锁
    echo "子进程 获取锁\n";
    $lock->unlock();//释放锁
    exit("子进程退出\n");
}
echo "主进程 释放锁\n";
unset($lock);
sleep(1);
echo "子进程退出\n";


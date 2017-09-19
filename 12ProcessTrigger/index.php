<?php
//触发函数 异步执行 执行到10自动停止
//swoole_process::signal(SIGALRM, function () {
//    echo "1\n";
//});
//swoole_process::alarm(100 * 1000);


swoole_process::signal(SIGALRM, function () {
    static $i = 0;
    echo "$i\n";
    $i++;
    if ($i > 10) {
        swoole_process::alarm(-1);
    }
});
swoole_process::alarm(100 * 1000);
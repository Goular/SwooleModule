<?php
//触发函数 异步执行 执行到10自动停止
swoole_process::signal(SIGALRM, function () {
    echo "1\n";
});
swoole_process::alarm(100 * 1000);
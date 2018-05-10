<?php

$content = date("Ymd H:i:s");
swoole_async_writefile(__DIR__ . "/2.log", $content, function ($fileName) {
    echo "Success " . PHP_EOL;
},FILE_APPEND);//FILE_APPEND 为追加文件标致
echo "Start" . PHP_EOL;
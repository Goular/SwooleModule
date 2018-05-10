<?php

// 读取文件
swoole_async_readfile(__DIR__ . "/tmp.txt", function ($fileName, $fileContent) {
    echo "fileName:" . $fileName . PHP_EOL;
    echo "content:" . $fileContent . PHP_EOL;
});
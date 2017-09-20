<?php
$content = "I Love GuangZhou！";
swoole_async_writefile("23.txt", $content, function ($filename) {
    echo $filename;
}, 0);
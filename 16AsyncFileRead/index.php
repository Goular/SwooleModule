<?php
swoole_async_readfile(__DIR__ . "12.txt", function ($filename, $content) {
    echo "$filename $content";
});
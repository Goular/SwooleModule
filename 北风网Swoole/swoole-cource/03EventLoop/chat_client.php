<?php
//客户端需要多开才能进行聊天室功能

$socket = stream_socket_client("tcp://127.0.0.1:7070", $errno, $errstr, 30);
function onRead()
{
    global $socket;
    $buffer = stream_socket_recvfrom($socket, 1024);
    if (!$buffer) {
        echo "Server Closed\n";
        swoole_event_del($socket);
    }
    echo "\nRECV:{$buffer}\n";
    fwrite(STDOUT, "Enter Msg1：");
}

function onWrite()
{
    global $socket;
    echo "on Write\n";
}

function onInput()
{
    global $socket;
    $msg = trim(fgets(STDIN));
    if ($msg == 'exit') {
        swoole_event_exit();
        exit();
    }
    swoole_event_write($socket, $msg);
    fwrite(STDOUT, "Enter Msg2:");
}

swoole_event_add($socket, 'onRead', 'onWrite');
swoole_event_add(STDIN, 'onInput');

fwrite(STDOUT, "Enter MSG3：");
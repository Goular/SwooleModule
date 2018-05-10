<?php
/**
 * Created by PhpStorm.
 * User: 27394
 * Date: 2017/4/21
 * Time: 15:26
 */
//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 7070);
//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    $GLOBALS['fd'][$request->fd]['id'] = $request->fd;// 设置用户ID 安顺序递增
    $GLOBALS['fd'][$request->fd]['name'] = '匿名用户';// 设置用户名
});
//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    $msg = $GLOBALS['fd'][$frame->fd]['name'].":{$frame->data}\n";
    if(strstr($frame->data,'#name#')){// 用户设置昵称
        $GLOBALS['fd'][$frame->fd]['name'] = str_replace('#name#','',$frame->data);
    }else{// 普通发送用户信息
        foreach($GLOBALS['fd'] as $i){// 发送数据到客户端
            $ws->push($i['id'],$msg);
        }
    }
});
//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "客户端-{$fd} 断开连接\n";
    unset($GLOBALS['fd'][$fd]);// 清除 已经关闭的客户端
});
$ws->start();
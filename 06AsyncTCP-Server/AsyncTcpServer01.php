<?php
//定义相关的配置属性
$host = "0.0.0.0";//主机地址,字段类型:String
$post = 7070;//端口号,字段类型:Integer，端口可以自定义

$serv = new swoole_server($host, $post);

//设置异步，进程工作数
$serv->set(['task_worker_num' => 4]);

//投递异步任务
$serv->on('receive', function (swoole_server $serv, $fd, $form_id, $data) {
    $task_id = $serv->task($data);//获取异步任务的ID
    echo "异步ID:" . $task_id . "\n";
});

//处理异步任务
$serv->on("task", function (swoole_server $serv, $task_id, $from_id, $data) {
    echo "执行异步任务的ID:$task_id \n";
    $serv->finish($data . "->ok");
});

//处理结果
$serv->on('finish', function (swoole_server $serv, $task_id, $data) {
    echo "执行完成" . $data."\n";
});

//启动TCP服务器
$serv->start();

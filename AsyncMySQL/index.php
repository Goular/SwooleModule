<?php
//mysql异步客户端
$db = new swoole_mysql();
$server = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'goular',
    'password' => '3071611103',
    'charset' => 'utf8'
];
$db->connect($server, function (swoole_mysql $db, $r) {
    if ($r === false) {
        var_dump($db->connect_errno, $db->connect_error);
        die;
    }
    $sql = "show tables;";
    $db->query($sql, function (swoole_mysql $db, $r) {
        if ($r === false) {
            var_dump($db->error, $db->errno);
        } elseif ($r === true) {
            var_dump($db->affected_rows, $db->insert_id);
        }
        var_dump($r);
        $db->close();
    });
});
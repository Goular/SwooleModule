<?php

class AsyncMysql
{
    public $dbSource = "";
    // mysql配置
    public $dbConfig = [];

    /**
     * AsyncMysql constructor.
     */
    public function __construct()
    {
        $this->dbSource = new Swoole\Mysql;
        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'zhao@307161',
            'database' => 'swoole',
            'charset' => 'utf8'
        ];
    }

    public function update()
    {
    }

    public function add()
    {
    }

    // 执行逻辑
    public function execute($id, $username)
    {
        $this->dbSource->connect($this->dbConfig, function ($db, $result) use ($id, $username) {
            echo "MySQL-Connect" . PHP_EOL;
            if ($result === false) {
                var_dump($db->connect_error);
                // TODO
            }

            $sql = "update test set `username`='" . $username . "' where id=" . $id;
            $db->query($sql, function ($db, $result) {
                if ($result === false) {
                    var_dump($db->error);
                } elseif ($result === true) {
                    var_dump($db->affected_rows);
                } else {
                    print_r($result);
                }
                $db->close();
            });
        });
        return true;
    }

}

$obj = new AsyncMysql();
$flag = $obj->execute('1', 'singwa-222222');
var_dump($flag) . PHP_EOL;
echo "start" . PHP_EOL;
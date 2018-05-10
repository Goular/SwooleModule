<?php
$serv = new swoole_http_server("127.0.0.1", 7070);
$serv->set([
    "worker_num" => 1
]);
$serv->on("Start", function () {
    swoole_set_process_name("simple_route_master");
});
$serv->on("ManagerStart", function () {
    swoole_set_process_name("simple_route_master");
});
$serv->on("WorkerStart", function () {
    swoole_set_process_name("simple_route_master");
    var_dump(spl_autoload_register(function ($class) {
        $baseClasspath = \str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
        $classpath = __DIR__ . "/" . $baseClasspath;
        if (is_file($classpath)) {
            require "{$classpath}";
            return;
        }
    }));
});
$serv->on("Request", function (swoole_http_request $request, $response) {
    $path_info = explode("/", $request->server["path_info"]);
    if (isset($path_info[1]) && !empty($path_infop[1])) {
        $ctrl = "ctrl\\" . $path_info[1];
    } else {
        $ctrl = "index";
    }
    if (isset($path_info[2])) {
        $action = $path_info[2];
    } else {
        $action = "index";
    }

    $result = "Ctrl not found";
    if (class_exists($ctrl)) {
        $class = new $ctrl();
        $result = "Action not found";
        if (method_exists($class, $action)) {
            $result = $class->action($request);
        }
    }
    $response->end($result);
});
$serv->start();
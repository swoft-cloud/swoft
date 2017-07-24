<?php

$server = new Swoole\Http\Server('127.0.0.1', 9501);

#1
$server->on('Request', function($request, $response) {
    // echo "request {$request->fd}\n";
    // $mysql = new Swoole\Coroutine\MySQL();

    // #2
    // $res = $mysql->connect([
    //     'host' => '192.168.101.3',
    //     'user' => 'gameva',
    //     'password' => 'v8hdDTJy3c2ri5YB',
    //     'database' => 'ugirlsweb_vr',
    // ]);

    // #3
    // if ($res == false) {
    //     $response->end("MySQL connect fail!");
    //     return;
    // }

    // $ret = $mysql->query('SHOW CREATE TABLE `ugirlsweb_vr`.`tAgentInfoJPush`', 2);
    // $response->end("swoole response is ok, result=".var_export($ret, true));
    $response->end("swoole response is ok, result=");
});

$server->set([
    'worker_num' => 4,
//    'dispatch_mode' => 1,
]);
$server->start();

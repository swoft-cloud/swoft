<?php
\Swoole\Runtime::enableCoroutine();

$http = new Swoole\Http\Server("0.0.0.0", 88);
$http->on('request', function ($request, $response) {
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});
$http->start();
<?php

return [
    'logger'     => [
        'flushRequest' => false,
        'enable'       => false,
        'json'         => false,
    ],
    'httpServer' => [
        'class'    => \Swoft\Http\Server\HttpServer::class,
        'port'     => 18306,
        'listener' => [
            'rpc' => \bean('rpcServer')
        ],
        'on'       => [
            \Swoft\Server\Swoole\SwooleEvent::TASK   => \bean(\Swoft\Task\Swoole\TaskListener::class),
            \Swoft\Server\Swoole\SwooleEvent::FINISH => \bean(\Swoft\Task\Swoole\FinishListener::class)
        ],
        'setting'  => [
            'task_worker_num'       => 1,
            'task_enable_coroutine' => true
        ]
    ],
    'user'       => [
        'class'   => \Swoft\Rpc\Client\Client::class,
        'host'    => '127.0.0.1',
        'port'    => 18307,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => \bean('rpcClientPacket')
    ],
    'user.pool'  => [
        'class'  => \Swoft\Rpc\Client\Pool::class,
        'client' => \bean('user')
    ],
    'rpcServer'  => [
        'class' => \Swoft\Rpc\Server\ServiceServer::class,
    ],
    'wsServer'   => [
        'on'      => [
            // Enable http handle
            \Swoft\Server\Swoole\SwooleEvent::REQUEST => bean(\Swoft\Http\Server\Swoole\RequestListener::class),
        ],
        /** @see \Swoft\WebSocket\Server\WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
];

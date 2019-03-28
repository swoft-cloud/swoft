<?php
return [
    \App\Model\Logic\DemoLogic::class => [
        [
            'dDname',
            12,
            '${App\Model\Data\DemoData}'
        ],
        'definitionData' => 'definitionData...'
    ],

    'httpServer' => [
        'class'    => \Swoft\Http\Server\HttpServer::class,
        'port'     => 88,
        'listener' => [
            'rpc' => \bean('rpcServer')
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
        'packet'  => \bean('clientPacket')
    ],
    'user.pool'  => [
        'class'  => \Swoft\Rpc\Client\Pool::class,
        'client' => bean('user')
    ],
    'rpcServer'  => [
        'class' => \Swoft\Rpc\Server\ServiceServer::class,
    ],
];
<?php
return [
    "redisPool" => [
        'class'           => \Swoft\Pool\RedisPool::class,
        "uri"             => [
            '127.0.0.1:6379',
            '127.0.0.1:6379'
        ],
        "maxIdel"         => 6,
        "maxActive"       => 10,
        "timeout"         => '${config.Service.user.timeout}',
        "balancer"        => '${randomBalancer}',
        "serviceName"     => 'redis',
        "useProvider"     => false,
        'serviceprovider' => '${consulProvider}'
    ],
];
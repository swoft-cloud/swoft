<?php
return [
    "dbMaster" => [
        "class"       => \Swoft\Pool\DbPool::class,
        "uri"         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8'
        ],
        "maxIdel"     => 6,
        "maxActive"   => 10,
        "timeout"     => 200,
        "balancer"    => '${randomBalancer}',
        "serviceName" => 'user',
        "useProvider" => false,
        'driver'      => \Swoft\Pool\DbPool::MYSQL
    ],
    "dbSlave"  => [
        "class"       => \Swoft\Pool\DbPool::class,
        "uri"         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8'
        ],
        "maxIdel"     => 6,
        "maxActive"   => 10,
        "timeout"     => 200,
        "balancer"    => '${randomBalancer}',
        "serviceName" => 'user',
        "useProvider" => false,
        'driver'      => \Swoft\Pool\DbPool::MYSQL
    ],
];
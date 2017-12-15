<?php
return [
    'redis' => [
        "uri"         => [
            '127.0.0.1:6379',
            '127.0.0.1:6379',
        ],
        "maxIdel"     => 6,
        "maxActive"   => 10,
        "timeout"     => 200,
        "balancer"    => 'random',
        "useProvider" => false,
        'provider'    => 'consul',
    ],
];
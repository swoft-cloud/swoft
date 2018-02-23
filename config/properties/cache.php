<?php
return [
    'redis' => [
        'name'        => 'redis',
        'uri'         => [
            '127.0.0.1:6379',
            '127.0.0.1:6379',
        ],
        'maxIdel'     => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'timeout'     => 8,
        'db'          => 1,
        'serialize'   => 0,
    ],
];
<?php

return [
    'application'      => [
        'id'          => APP_NAME,
        'name'        => APP_NAME,
        'errorAction' => '/error/index',
        'useProvider' => false,
    ],

    'ServerDispatcher' => [
        'middlewares' => [
            \Swoft\View\Middleware\ViewMiddleware::class
        ]
    ],
    'httpRouter'       => [
        'ignoreLastSep'  => false,
        'tmpCacheNumber' => 1000,
        'matchAll'       => '',
    ],
    'requestParser'    => [
        'parsers' => [

        ],
    ],
    'view'         => [
        'viewsPath' => '@resources/views/',
    ],
    'cache' => [
        'driver' => 'redis',
        'drivers' => [
            'redis' => \Swoft\Redis\Redis::class
        ]
    ]
];

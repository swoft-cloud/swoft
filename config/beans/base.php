<?php

return [
    'ServerDispatcher' => [
        'middlewares' => [
            \Swoft\View\Middleware\ViewMiddleware::class,
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
    'view'             => [
        'viewsPath' => '@resources/views/',
    ],
    'cache'            => [
        'driver' => 'redis',
    ]
];

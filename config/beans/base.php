<?php

return [
    'dispatcherServer' => [
        'class' => \Swoft\Http\Server\DispatcherServer::class,
        'middlewares' => [
            \Swoft\View\Middleware\ViewMiddleware::class
        ]
    ],
    'application'      => [
        'id'          => APP_NAME,
        'name'        => APP_NAME,
        'errorAction' => '/error/index',
        'useProvider' => false,
    ],
    'balancerSelector' => [
        'class'     => \Swoft\Pool\BalancerSelector::class,
        'balancers' => [

        ],
    ],
    'providerSelector' => [
        'class'     => \Swoft\Pool\ProviderSelector::class,
        'providers' => [

        ],
    ],
    'httpRouter'       => [
        'class'          => \Swoft\Http\Server\Router\HandlerMapping::class,
        'ignoreLastSep'  => false,
        'tmpCacheNumber' => 1000,
        'matchAll'       => '',
    ],
    'requestParser'    => [
        'class'   => \Swoft\Http\Server\Parser\RequestParser::class,
        'parsers' => [

        ],
    ],
    'view'         => [
        'class'     => \Swoft\View\Base\View::class,
        'viewsPath' => '@resources/views/',
    ],
    'eventManager'     => [
        'class' => \Swoft\Event\EventManager::class,
    ],
    'cache' => [
        'class' => \Swoft\Cache\Cache::class,
        'driver' => 'redis',
        'drivers' => [
            'redis' => \Swoft\Redis\RedisCache::class
        ]
    ]
];

<?php

return [
    'dispatcherServer' => [
        'class' => \Swoft\Web\DispatcherServer::class,
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
        'class'          => \Swoft\Router\Http\HandlerMapping::class,
        'ignoreLastSep'  => false,
        'tmpCacheNumber' => 1000,
        'matchAll'       => '',
    ],
    'requestParser'    => [
        'class'   => \Swoft\Web\RequestParser::class,
        'parsers' => [

        ],
    ],
    'renderer'         => [
        'class'     => \Swoft\Web\ViewRenderer::class,
        'viewsPath' => '@resources/views/',
    ],
    'eventManager'     => [
        'class' => \Swoft\Event\EventManager::class,
    ],
    'cache' => [
        'class' => \Swoft\Cache\Cache::class,
        'driver' => 'redis',
    ]
];

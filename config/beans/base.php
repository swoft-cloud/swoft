<?php

return [
    'dispatcherServer' => [
        'class' => \Swoft\Web\DispatcherServer::class
    ],
    'application' => [
        'id'          => APP_NAME,
        'name'        => APP_NAME,
        'errorAction' => '/error/index',
        'useProvider' => false,
    ],
    'httpRouter'      => [
        'class'          => \Swoft\Router\Http\HandlerMapping::class,
        'ignoreLastSep'  => false,
        'tmpCacheNumber' => 1000,
        'matchAll'       => '',
    ],
    'requestParser' =>[
        'class' => \Swoft\Web\RequestParser::class
    ],
    'renderer'    => [
        'class'     => \Swoft\Web\ViewRenderer::class,
        'viewsPath' => '@resources/views/',
    ],
    'eventManager'    => [
        'class'     => \Swoft\Event\EventManager::class,
    ],
];

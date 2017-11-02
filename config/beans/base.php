<?php

return [
    'application' => [
        'id' => APP_NAME,
        'name' => APP_NAME,
        'errorAction' => '/error/index',
        'useProvider' => false,
    ],
    'router' => [
        'class' => \Swoft\Web\Router::class,
        'ignoreLastSep' => false,
        'tmpCacheNumber' => 1000,
        'matchAll' => '',
    ],
    'renderer' => [
        'class' => \Swoft\Web\ViewRenderer::class,
        'viewsPath' => dirname(__DIR__) . '/resources/views/',
    ],
];

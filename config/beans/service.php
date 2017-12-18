<?php
return [
    'dispatcherService' => [
        'class' => \Swoft\Service\DispatcherService::class,
    ],
    'serviceRouter'     => [
        'class' => \Swoft\Router\Service\HandlerMapping::class,
    ],
    'servicePacker'     => [
        'class'   => \Swoft\Service\ServicePacker::class,
        'type'    => 'json',
        'packers' => [

        ],
    ],
];
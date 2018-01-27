<?php
return [
    'dispatcherService' => [
        'class' => \Swoft\Rpc\Server\DispatcherService::class,
    ],
    'serviceRouter'     => [
        'class' => \Swoft\Rpc\Server\Router\HandlerMapping::class,
    ],
    'servicePacker'     => [
        'class'   => \Swoft\Rpc\Packer\ServicePacker::class,
        'type'    => 'json',
        'packers' => [
            'json' => JsonPacker::class,
        ],
    ],
];
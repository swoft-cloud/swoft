<?php
return [
    'ServiceDispatcher' => [
        'class' => \Swoft\Rpc\Server\ServiceDispatcher::class,
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
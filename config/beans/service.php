<?php
return [
    'dispatcherService' => [
        'class' => \Swoft\Service\DispatcherService::class,
    ],
    'serviceRouter'     => [
        'class' => \Swoft\Router\Service\HandlerMapping::class,
    ],
    'servicePacker'     => [
        'class' => \Swoft\Service\ServicePacker::class,
        'type'  => 'json',
    ],
    'consulProvider'    => [
        'class'   => \Swoft\Service\ConsulProvider::class,
        'address' => '127.0.0.1:80',
    ],
    "userPool"          => [
        "class"           => \Swoft\Pool\ServicePool::class,
        "uri"             => [
            '127.0.0.1:8099',
            '127.0.0.1:8099',
        ],
        "maxIdel"         => 6,
        "maxActive"       => 10,
        "timeout"         => '${config.Service.user.timeout}',
        "balancer"        => '${randomBalancer}',
        "serviceName"     => 'user',
        "useProvider"     => false,
        'serviceprovider' => '${consulProvider}',
    ],
    "userBreaker"       => [
        'class'               => \Swoft\Circuit\CircuitBreaker::class,
        'swithToSuccessCount' => 6, // 请求成功次数上限(状态切换)
        'swithToFailCount'    => 6, // 请求失败次数上限(状态切换)
        'delaySwithTimer'     => 5000, // 开启状态切换到半开状态的延迟时间，单位毫秒
    ],
];
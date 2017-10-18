<?php

return [
    'config'       => [
        'properties' => require_once __DIR__ . '/' . APP_ENV . '/properties.php',
    ],
    'application'  => [
        'id'          => SYSTEM_NAME,
        'name'        => SYSTEM_NAME,
        'errorAction' => '/error/index',
        'useProvider' => false,
    ],
    'router'       => [
        'class'               => \Swoft\Web\Router::class,
        'ignoreLastSep'       => false,
        'tmpCacheNumber'      => 1000,
        'matchAll'            => '',
    ],
    'renderer' => [
        'class' => \Swoft\Web\ViewRenderer::class,
        'viewsPath' => dirname(__DIR__) . '/resources/views',
    ],
    'commonParamsFilter' => [
        'class'      => \App\beans\Filters\CommonParamsFilter::class,
        'uriPattern' => '/*',
    ],
    'loginFilter'        => [
        'class'      => \App\beans\Filters\LoginFilter::class,
        'uriPattern' => '/index/login,/index/login2',
    ],
    'Filter'             => [
        'filters' => [
            '${commonParamsFilter}',
            '${loginFilter}',
        ],
    ],

    "dbMaster" => [
        "class"       => \Swoft\Pool\DbPool::class,
        "uri"         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8'
        ],
        "maxIdel"     => 6,
        "maxActive"   => 10,
        "timeout"     => 200,
        "balancer"    => '${randomBalancer}',
        "serviceName" => 'user',
        "useProvider" => false,
        'driver'      => \Swoft\Pool\DbPool::MYSQL
    ],
    "dbSlave" => [
        "class"       => \Swoft\Pool\DbPool::class,
        "uri"         => [
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8'
        ],
        "maxIdel"     => 6,
        "maxActive"   => 10,
        "timeout"     => 200,
        "balancer"    => '${randomBalancer}',
        "serviceName" => 'user',
        "useProvider" => false,
        'driver'      => \Swoft\Pool\DbPool::MYSQL
    ],

    'consulProvider'     => [
        'class'   => \Swoft\Service\ConsulProvider::class,
        'address' => '127.0.0.1:80'
    ],
    "userPool"           => [
        "class"           => \Swoft\Pool\ServicePool::class,
        "uri"             => [
            '127.0.0.1:8099',
            '127.0.0.1:8099'
        ],
        "maxIdel"         => 6,
        "maxActive"       => 10,
        "timeout"         => '${config.Service.user.timeout}',
        "balancer"        => '${randomBalancer}',
        "serviceName"     => 'user',
        "useProvider"     => false,
        'serviceprovider' => '${consulProvider}'
    ],

    "redisPool" => [
        'class'           => \Swoft\Pool\RedisPool::class,
        "uri"             => [
            '127.0.0.1:6379',
            '127.0.0.1:6379'
        ],
        "maxIdel"         => 6,
        "maxActive"       => 10,
        "timeout"         => '${config.Service.user.timeout}',
        "balancer"        => '${randomBalancer}',
        "serviceName"     => 'redis',
        "useProvider"     => false,
        'serviceprovider' => '${consulProvider}'
    ],

    "userBreaker" => [
        'class'               => \Swoft\Circuit\CircuitBreaker::class,
        'swithToSuccessCount' => 6, // 请求成功次数上限(状态切换)
        'swithToFailCount'    => 6, // 请求失败次数上限(状态切换)
        'delaySwithTimer'     => 5000, // 开启状态切换到半开状态的延迟时间，单位毫秒
    ],

    "noticeHandler"      => [
        "class"     => \Swoft\Log\FileHandler::class,
        "logFile"   =>  "@runtime/notice.Log",
        'formatter' => '${lineFormate}',
        "levels"    => [
            \Swoft\Log\Logger::NOTICE,
            \Swoft\Log\Logger::INFO,
            \Swoft\Log\Logger::DEBUG,
            \Swoft\Log\Logger::TRACE,
        ]
    ],
    "applicationHandler" => [
        "class"     => \Swoft\Log\FileHandler::class,
        "logFile"   => "@runtime/error.Log",
        'formatter' => '${lineFormate}',
        "levels"    => [
            \Swoft\Log\Logger::ERROR,
            \Swoft\Log\Logger::WARNING
        ]
    ],
    "logger"             => [
        "class"         => \Swoft\Log\Logger::class,
        "name"          => SYSTEM_NAME,
        "flushInterval" => 100,
        "flushRequest"  => true,
        "handlers"      => [
            '${noticeHandler}',
            '${applicationHandler}'
        ]
    ]
];

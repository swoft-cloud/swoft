<?php
return [
    'config'      => [
        'properties' => require_once __DIR__ . '/' . APP_ENV . '/properties.php',
    ],
    'application' => [
        'id'          => SYSTEM_NAME,
        'name'        => SYSTEM_NAME,
        'viewsPath'   => VIEWS_PATH,
        'runtimePath' => RUNTIME_PATH,
        'settingPath' => SETTING_PATH,
        'basePath'    => dirname(__DIR__),
        'useProvider' => false
    ],
    'router'      => [
        'class'  => \swoft\web\Router::class,
        'config' => [
            'ignoreLastSep'  => false,
            'tmpCacheNumber' => 100,
            'matchAll'       => '',

            // auto route match @like yii framework
            'autoRoute'      => [
                'enable'              => true,
                'controllerNamespace' => 'app\\controllers',
                'controllerSuffix'    => 'Controller',
            ],
        ]
    ],

    'commonParamsFilter' => [
        'class'      => \app\beans\filters\CommonParamsFilter::class,
        'uriPattern' => '/*',
    ],
    'loginFilter'        => [
        'class'      => \app\beans\filters\LoginFilter::class,
        'uriPattern' => '/index/login',
    ],
    'filter'             => [
        'filters' => [
            '${commonParamsFilter}',
            '${loginFilter}',
        ],
    ],
    'userProvider'       => [
        'class' => \swoft\service\ConsulProvider::class
    ],
    "userPool"           => [
        "class"           => \swoft\pool\ServicePool::class,
        "uri"             => '127.0.0.1:8099,127.0.0.1:8099',
        "maxIdel"         => 6,
        "maxActive"       => 10,
        "timeout"         => '${config.service.user.timeout}',
        "balancer"        => '${randomBalancer}',
        "serviceName"     => 'user',
        "useProvider"     => false,
        'serviceprovider' => '${userProvider}'
    ],
    "redisPool"          => [
        'class'     => \swoft\pool\RedisPool::class,
        "maxIdel"   => 6,
        "maxActive" => 10,
        "timeout"   => 200,
    ],

    "userBreaker" => [
        'class'           => \swoft\circuit\CircuitBreaker::class,
        'delaySwithTimer' => 8000
    ],

    "noticeHandler"      => [
        "class"     => \swoft\log\FileHandler::class,
        "logFile"   => RUNTIME_PATH . "/notice.log",
        'formatter' => '${lineFormate}',
        "levels"    => [
            \swoft\log\Logger::NOTICE,
            \swoft\log\Logger::INFO,
            \swoft\log\Logger::DEBUG,
            \swoft\log\Logger::TRACE,
        ]
    ],
    "applicationHandler" => [
        "class"     => \swoft\log\FileHandler::class,
        "logFile"   => RUNTIME_PATH . "/error.log",
        'formatter' => '${lineFormate}',
        "levels"    => [
            \swoft\log\Logger::ERROR,
            \swoft\log\Logger::WARNING
        ]
    ],
    "logger" => [
        "class"         => \swoft\log\Logger::class,
        "name"          => SYSTEM_NAME,
        "flushInterval" => 1,
        "handlers"      => [
            '${noticeHandler}',
            '${applicationHandler}'
        ]
    ]
];

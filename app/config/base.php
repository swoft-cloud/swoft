<?php
return [
    'config'         => [
        'properties' => require_once __DIR__ . '/' . APP_ENV . '/properties.php',
    ],
    'application'    => [
        'id'          => SYSTEM_NAME,
        'name'        => SYSTEM_NAME,
        'viewsPath'   => VIEWS_PATH,
        'runtimePath' => RUNTIME_PATH,
        'settingPath' => SETTING_PATH,
        'basePath'    => dirname(__DIR__),
        'useProvider' => false
    ],
    'router' => [
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
    'filter'         => [
        'filters' => [
            'commonParamsFilter' => [
                'class'      => 'app\beans\filters\CommonParamsFilter',
                'uriPattern' => '/*',
            ],
            'loginFilter'        => [
                'class'      => 'app\beans\filters\LoginFilter',
                'uriPattern' => '/index/login',
            ]
        ]
    ],

    "userPool"  => [
        "class"       => \swoft\pool\ServicePool::class,
        "uri"         => '127.0.0.1:8099,127.0.0.1:8099',
        "maxIdel"     => 6,
        "maxActive"   => 10,
        "timeout"     => '${config.service.user.timeout}',
        "balancer"    => '${randomBalancer}',
        "serviceName" => 'user',
        "useProvider" => false
    ],
    "redisPool" => [
        'class'     => \swoft\pool\RedisPool::class,
        "maxIdel"   => 6,
        "maxActive" => 10,
        "timeout"   => 200,
    ],

    "userBreaker"        => [
        'class'           => \swoft\circuit\CircuitBreaker::class,
        'delaySwithTimer' => 8000
    ],

    "lineFormate" =>[
        'class' => \Monolog\Formatter\LineFormatter::class,
        "format" => '%datetime% [%level_name%] [%channel%] [logid:%logid%] [spanid:%spanid%] %message%',
        'dateFormat' => 'Y/m/d H:i:s'
    ],
    "noticeHandler"      => [
        "class"   => \swoft\log\FileHandler::class,
        "logFile" => RUNTIME_PATH . "/notice.log",
        'formatter' => '${lineFormate}',
        "levels"  => [
            \swoft\log\Logger::NOTICE,
            \swoft\log\Logger::INFO,
            \swoft\log\Logger::DEBUG,
            \swoft\log\Logger::TRACE,
        ]
    ],
    "applicationHandler" => [
        "class"   => \swoft\log\FileHandler::class,
        "logFile" => RUNTIME_PATH . "/error.log",
        'formatter' => '${lineFormate}',
        "levels"  => [
            \swoft\log\Logger::ERROR,
            \swoft\log\Logger::WARNING
        ]
    ],
    "logger"             => [
        "class"   => \swoft\log\Logger::class,
        "name"    => SYSTEM_NAME,
        "handlers" => [
            '${noticeHandler}',
            '${applicationHandler}'
        ]
    ]
];

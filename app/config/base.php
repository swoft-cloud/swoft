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
        'ignoreLastSep'  => false,
        'tmpCacheNumber' => 1000,
        'matchAll'       => '',

        // auto route match @like yii framework
        'autoRoute'      => true,
        'controllerNamespace' => 'app\\controllers',
        'controllerSuffix'    => 'Controller',
    ],
    'errorHanlder' => [
        'class' => \swoft\web\ErrorHandler::class,
        'errorAction' => '/error/index',
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
    'consulProvider'       => [
        'class' => \swoft\service\ConsulProvider::class,
        'address' => '127.0.0.1:80'
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
        'serviceprovider' => '${consulProvider}'
    ],

    "redisPool"          => [
        'class'     => \swoft\pool\RedisPool::class,
        "uri"             => '127.0.0.1:6379,127.0.0.1:6379',
        "maxIdel"         => 6,
        "maxActive"       => 10,
        "timeout"         => '${config.service.user.timeout}',
        "balancer"        => '${randomBalancer}',
        "serviceName"     => 'redis',
        "useProvider"     => false,
        'serviceprovider' => '${consulProvider}'
    ],

    "userBreaker" => [
        'class'           => \swoft\circuit\CircuitBreaker::class,
        'swithToSuccessCount' => 6, // 请求成功次数上限(状态切换)
        'swithToFailCount' => 6, // 请求失败次数上限(状态切换)
        'delaySwithTimer' => 5000, // 开启状态切换到半开状态的延迟时间，单位毫秒
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

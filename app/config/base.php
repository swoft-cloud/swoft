<?php
!defined('SYSTEM_NAME') && define('SYSTEM_NAME', 'swoft');
!defined('APP_PATH') && define('APP_PATH',  dirname(__FILE__).'/../../');
!defined('RUNTIME_PATH') && define('RUNTIME_PATH',  APP_PATH.'runtime/' . SYSTEM_NAME);
!defined('VIEWS_PATH') && define('VIEWS_PATH',  APP_PATH.'app/views');
!defined('SETTING_PATH') && define('SETTING_PATH',  APP_PATH.'bin/swoft.ini');

$config = \swoft\helpers\ArrayHelper::merge(
    [],
    [
        'id' => SYSTEM_NAME,
        'name' => SYSTEM_NAME,
        'viewsPath'   => VIEWS_PATH,
        'runtimePath' => RUNTIME_PATH,
        'settingPath' => SETTING_PATH,
        'basePath' =>dirname( __DIR__),
        'beans' => [
            'urlManager' => [
                'rules' => [
                    '/home/data' => '/index',
                    '/index/index/1' => '/index/index',
                    '/post/<id:\d+>' => 'post/view'
                ],
            ],
            'filter' => [
                'filters' =>[
                    'commonParamsFilter' => [
                        'class' => 'app\beans\filters\CommonParamsFilter',
                        'uriPattern' => '/*',
                    ],
                    'loginFilter' => [
                        'class' => 'app\beans\filters\LoginFilter',
                        'uriPattern' => '/index/login',
                    ]
                ]
            ],
            'managerPool' =>[
                'class' => \swoft\pool\ManagerPool::class,
                'services' =>[
                    "user" => [
                        "class" => \swoft\pool\ServicePool::class,
                        "maxIdel" => 6,
                        "maxActive" => 10,
                        "timeout" => 200,
                    ]
                ]
            ],
            "circuitBreakerManager" =>[
                'class' => \swoft\circuit\CircuitBreakerManager::class,
                'swithToFailCount' => 10,
                'swithToSuccessCount' => 10,
                'delaySwithTimer' => 20000
            ],
//            "logger" =>[
//                'class' => \swoft\log\Logger::class,
//                "targets" => [
//                    [
//                        "class" => \swoft\log\FileHandler::class,
//                        "logFile" => RUNTIME_PATH."/notice.log",
//                        "levels" => [
//                            \swoft\log\Logger::NOTICE,
//                            \swoft\log\Logger::INFO,
//                            \swoft\log\Logger::DEBUG,
//                        ]
//                    ],
//                    [
//                        "class" => \swoft\log\FileHandler::class,
//                        "logFile" => RUNTIME_PATH."/error.log",
//                        "levels" => [
//                            \swoft\log\Logger::ERROR,
//                            \swoft\log\Logger::WARNING
//                        ]
//                    ]
//                ]
//            ]
        ],
        'params' =>[
            'version' => '1.1.0'
        ]
    ]
);

return $config;
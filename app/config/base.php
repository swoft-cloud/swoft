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
            'rpcClient' =>[
                'class' => \swoft\rpc\RpcClient::class,
                'services' =>[
                    \swoft\rpc\RpcClient::USER_SERVICE => [
                        'host' => '127.0.0.1',
                        'port' => 8099,
                        'timeout' => 0.5,
                        'size' => 6
                    ]
                ]
            ]
        ],
        'params' =>[
            'version' => '1.1.0'
        ]
    ]
);

return $config;
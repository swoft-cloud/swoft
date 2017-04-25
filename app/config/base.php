<?php
!defined('SYSTEM_NAME') && define('SYSTEM_NAME', 'swoft');
!defined('APP_PATH') && define('APP_PATH',  dirname(__FILE__).'/../../');
!defined('RUNTIME_PATH') && define('RUNTIME_PATH',  APP_PATH.'runtime/' . SYSTEM_NAME);
!defined('SETTING_PATH') && define('SETTING_PATH',  APP_PATH.'bin/swoft.ini');

$config = \swoft\helpers\ArrayHelper::merge(
    require_once __DIR__. '/beans.php',
    [
        'id' => SYSTEM_NAME,
        'name' => SYSTEM_NAME,
        'runtimePath' => RUNTIME_PATH,
        'settingPath' => SETTING_PATH,
        'basePath' =>dirname( __DIR__),
        'beansNamespace' => 'app\controllers',

        'params' =>[
            'version' => '1.1.0'
        ]
    ]
);

return $config;
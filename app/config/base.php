<?php
!defined('SYSTEM_NAME') && define('SYSTEM_NAME', 'swoft');
!defined('APP_PATH') && define('APP_PATH',  dirname(__FILE__).'/../../');
!defined('RUNTIME_PATH') && define('RUNTIME_PATH',  APP_PATH.'runtime/' . SYSTEM_NAME);
!defined('SETTING_PATH') && define('SETTING_PATH',  APP_PATH.'bin/swoft.ini');

$config = [
    'id' => SYSTEM_NAME,
    'basePath' =>dirname( __DIR__),
    'name' => SYSTEM_NAME,
    'runtimePath' => RUNTIME_PATH,
    'settingPath' => SETTING_PATH,
];
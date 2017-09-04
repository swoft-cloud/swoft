<?php
// beans配置
$definitions = require_once BASE_PATH . '/config/base.php';
$beanFactory = new \Swoft\Di\BeanFactory($definitions);

$initApplicationContext = new \Swoft\Base\InitApplicationContext();
$initApplicationContext->registerListeners();
$initApplicationContext->applicationLoader();

// 重新加载路由
require_once BASE_PATH . '/app/routes.php';

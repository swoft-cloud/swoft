<?php
// beans配置
$definitions = require_once BASE_PATH . '/config/base.php';

// 初始化beans
$beanFactory = new \Swoft\Di\BeanFactory($definitions);

// 重新加载路由
require_once BASE_PATH . '/app/routes.php';

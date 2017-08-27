<?php
// beans配置
$definitions = require_once BASE_PATH . '/app/config/base.php';

// 初始化beans
$beanFactory = new \swoft\di\BeanFactory($definitions);

// 重新加载路由
require_once BASE_PATH . '/app/routes.php';
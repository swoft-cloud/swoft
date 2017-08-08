<?php
// 系统名称
define('SYSTEM_NAME', 'swoft');
// 基础目录
define('BASE_PATH', dirname(__DIR__, 2));
// 应用目录
define('APP_PATH', BASE_PATH . '/app');
// 日志目录
define('RUNTIME_PATH', BASE_PATH . '/runtime/' . SYSTEM_NAME);
// 视图目录
define('VIEWS_PATH', APP_PATH . '/views');
// swoft启动配置文件
define('SETTING_PATH', BASE_PATH . '/bin/swoft.ini');


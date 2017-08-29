<?php
// 系统名称
define('SYSTEM_NAME', 'swoft');
// auto reload
define('AUTO_RELOAD', true);
// 基础根目录
define('BASE_PATH', dirname(__DIR__, 1));

require_once dirname(__FILE__, 2) . "/vendor/autoload.php";
require_once dirname(__FILE__, 2) . '/app/config/define.php';
require_once dirname(__FILE__, 2) . '/app/config/model.php';

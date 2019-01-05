<?php
// Project base path
!defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__));

// Project application path
!defined('APP_PATH') && define('APP_PATH', BASE_PATH . '/app');

// Project config path
!defined('CONFIG_PATH') && define('CONFIG_PATH', BASE_PATH . '/config');

// Composer autoload
require_once BASE_PATH . '/vendor/autoload.php';
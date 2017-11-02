<?php

use \Swoft\App;

// Constants
! defined('DS') && define('DS', DIRECTORY_SEPARATOR);
// 系统名称
! defined('APP_NAME') && define('APP_NAME', 'swoft');
// auto reload
! defined('AUTO_RELOAD') && define('AUTO_RELOAD', true);
// 基础根目录
! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

// Alias
App::setAlias('@root', BASE_PATH);
App::setAlias('@app', '@root/app');
App::setAlias('@res', '@root/resources');
App::setAlias('@runtime', '@root/runtime/' . APP_NAME);
App::setAlias('@configs', '@root/config');
App::setAlias('@beans', '@configs/beans');
App::setAlias('@properties', '@configs/properties');
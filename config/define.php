<?php

// Constants
! defined('DS') && define('DS', DIRECTORY_SEPARATOR);
// App name
! defined('APP_NAME') && define('APP_NAME', 'swoft');
// Project base path
! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

// Register alias
$aliases = [
    '@root'       => BASE_PATH,
    '@app'        => '@root/app',
    '@res'        => '@root/resources',
    '@runtime'    => '@root/runtime',
    '@configs'    => '@root/config',
    '@resources'  => '@root/resources',
    '@beans'      => '@configs/beans',
    '@properties' => '@configs/properties',
    '@console'    => '@beans/console.php',
    '@commands'   => '@app/command',
];

\Swoft\App::setAliases($aliases);

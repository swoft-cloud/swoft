<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

! defined('DS') && define('DS', DIRECTORY_SEPARATOR);
// App name
! defined('APP_NAME') && define('APP_NAME', 'swoft');
// Project base path
! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

// Register alias
$aliases = [
    '@root'       => BASE_PATH,
    '@env'        => '@root',
    '@app'        => '@root/app',
    '@res'        => '@root/resources',
    '@runtime'    => '@root/runtime',
    '@configs'    => '@root/config',
    '@resources'  => '@root/resources',
    '@beans'      => '@configs/beans',
    '@properties' => '@configs/properties',
    '@console'    => '@beans/console.php',
    '@commands'   => '@app/command',
    '@vendor'     => '@root/vendor',
];

\Swoft\App::setAliases($aliases);

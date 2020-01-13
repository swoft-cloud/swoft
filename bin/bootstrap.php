<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

$loader->addPsr4("Swoft\\Cache\\", 'vendor/swoft/cache/src/');
$loader->addPsr4("Swoft\\Swlib\\", 'vendor/swoft/swlib/src/');
$loader->addPsr4("Swoft\\Serialize\\", 'vendor/swoft/serialize/src/');

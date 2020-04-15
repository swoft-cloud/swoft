<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use SwoftTest\Testing\TestApplication;

$baseDir = dirname(__DIR__);
$vendor  = dirname(__DIR__) . '/vendor';

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->addPsr4('SwoftTest\\Testing\\', $vendor . '/swoft/framework/test/testing/');

$application = new TestApplication($baseDir);
$application->setBeanFile($baseDir . '/app/bean.php');
$application->run();

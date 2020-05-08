<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use AppTest\Testing\TestApplication;

$baseDir = dirname(__DIR__);
$vendor  = dirname(__DIR__) . '/vendor';

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

$swoftFwDir = $vendor . '/swoft/framework';

// in framework developing
if (file_exists($vendor . '/swoft/component/src/framework')) {
    $swoftFwDir = $vendor . '/swoft/component/src/framework';
}

$loader->addPsr4('AppTest\\Unit\\', $baseDir . '/test/unit/');
$loader->addPsr4('AppTest\\Testing\\', $baseDir . '/test/testing/');
$loader->addPsr4('SwoftTest\\Testing\\', $swoftFwDir . '/test/testing/');
// $loader->addPsr4('Swoft\\Swlib\\', $vendor . '/swoft/swlib/src/');

$app = new TestApplication($baseDir);
$app->run();

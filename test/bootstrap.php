<?php

use SwoftTest\Testing\TestApplication;

$baseDir = dirname(__DIR__);
$vendor  = dirname(__DIR__) . '/vendor';

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->addPsr4("SwoftTest\\Testing\\", $vendor . '/swoft/framework/test/testing/');

$application = new TestApplication([
    'basePath' => $baseDir,
]);
$application->setBeanFile($baseDir . '/app/bean.php');
$application->run();

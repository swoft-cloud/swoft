<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/define.php';

// init the factory of bean
\Swoft\Bean\BeanFactory::init();

/* @var \Swoft\Bootstrap\Boots\Bootable $bootstrap*/
$bootstrap = \Swoft\App::getBean(\Swoft\Bootstrap\Bootstrap::class);
$bootstrap->bootstrap();

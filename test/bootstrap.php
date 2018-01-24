<?php
require_once dirname(dirname(__FILE__)) . "/vendor/autoload.php";
require_once dirname(dirname(__FILE__)) . '/config/define.php';

// init
\Swoft\App::$isInTest = true;

\Swoft\Bean\BeanFactory::init();
\Swoft\Bean\BeanFactory::reload([
    'application' => [
        'class' => \Swoft\Testing\Application::class,
        'inTest' => true
    ],
]);

$server = new \Swoft\Http\Server\Http\HttpServer();

$initApplicationContext = new \Swoft\Core\InitApplicationContext();
$initApplicationContext->init();
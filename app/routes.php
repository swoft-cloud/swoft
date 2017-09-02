<?php
use App\Controllers\IndexController;

/* @var \Swoft\Web\Router $router */
$router = \Swoft\App::getBean('router');

$router->get('/', IndexController::class);

<?php
use app\controllers\IndexController;

/* @var \swoft\web\Router $router */
$router = \swoft\App::getBean('router');
$router->get('/', IndexController::class);

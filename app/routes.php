<?php
/**
 * @var \inhere\sroute\ORouter $router
 */
use app\controllers\IndexController;

$router->get('/', IndexController::class);

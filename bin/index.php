<?php
require_once '../vendor/autoload.php';

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAnnotations(true);
$container = $containerBuilder->build();

$container->set(\app\controllers\IndexController::class, \DI\object('\app\controllers\IndexController'));
$object = $container->get(\app\controllers\IndexController::class);

$result = $object->actionIndex();

var_dump($result);






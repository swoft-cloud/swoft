<?php
require_once __DIR__. '/bootstrap.php';

$router = \swoft\base\ApplicationContext::getBean('router');

require dirname(__DIR__) . '/app/routes.php';

/* @var  \swoft\web\Application $application */
$application = \swoft\base\ApplicationContext::getBean('application');
$application->run();

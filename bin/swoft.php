<?php
use swoft\base\ApplicationContext;

require_once __DIR__. '/bootstrap.php';
require dirname(__DIR__) . '/app/routes.php';

/* @var  \swoft\web\Application $application */
$application = ApplicationContext::getBean('application');
$application->run();

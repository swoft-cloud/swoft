<?php
require_once __DIR__. '/bootstrap.php';

/* @var  \swoft\web\Application $application */
$application = \swoft\base\ApplicationContext::getBean('application');
$application->run();




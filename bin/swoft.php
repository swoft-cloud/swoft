<?php
require_once __DIR__. '/bootstrap.php';

/* @var  \swoft\web\Application $application */
$application = \swoft\helpers\BeanFactory::getBean('application');
$application->run();





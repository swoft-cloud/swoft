<?php
require_once __DIR__. '/bootstrap.php';

$application = \swoft\helpers\BeanFactory::getBean('application');
$application->run();







<?php
require_once __DIR__. '/../vendor/autoload.php';
require_once __DIR__. '/../app/config/define.php';
require_once __DIR__. '/../app/config/model.php';

$config = require_once __DIR__. "/../app/config/base.php";
$beanFactory = new \swoft\di\BeanFactory($config);


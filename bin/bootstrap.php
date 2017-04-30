<?php
require_once __DIR__. '/../vendor/autoload.php';
require_once __DIR__. '/../app/config/model.php';

$config = require_once __DIR__. '/../app/config/'.strtolower(APP_ENV)."/main.php";
$swoftInitializer = new \swoft\SwoftInitializer();
$swoftInitializer->init($config);


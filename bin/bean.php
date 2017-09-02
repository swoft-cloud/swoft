<?php
// 系统名称
define('SYSTEM_NAME', 'swoft');
// auto reload
define('AUTO_RELOAD', true);
// 基础根目录
define('BASE_PATH', dirname(__DIR__, 1));

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once __DIR__. '/../vendor/autoload.php';
require_once __DIR__ . '/../config/define.php';
require_once __DIR__ . '/../config/Model.php';

$config = require dirname(__DIR__) . '/config/Base.php';

$beanFactory = new \Swoft\Di\BeanFactory($config);

\Swoft\App::setAlias("app", __DIR__);
//var_dump($beanFactory::getBean(\App\Controllers\IndexController::class));
//var_dump($beanFactory::getBean('userModel'));
//var_dump($beanFactory::getBean(\App\Models\Model\UserModel::class));
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
require_once __DIR__. '/../app/config/define.php';
require_once __DIR__. '/../app/config/model.php';

$config = require dirname(__DIR__). '/app/config/base.php';

$beanFactory = new \swoft\di\BeanFactory($config);

\swoft\App::setAlias("app", __DIR__);
//var_dump($beanFactory::getBean(\app\controllers\IndexController::class));
//var_dump($beanFactory::getBean('userModel'));
//var_dump($beanFactory::getBean(\app\models\model\UserModel::class));
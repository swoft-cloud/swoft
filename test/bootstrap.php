<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/define.php';

// init
\Swoft\App::$isInTest = true;

\Swoft\Bean\BeanFactory::init();
\Swoft\Bean\BeanFactory::reload([
    'application' => [
        'class' => \Swoft\Testing\Application::class,
        'inTest' => true
    ],
]);

$initApplicationContext = new \Swoft\Core\InitApplicationContext();
$initApplicationContext->init();
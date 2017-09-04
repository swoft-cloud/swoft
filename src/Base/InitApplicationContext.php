<?php

namespace Swoft\Base;

use Swoft\App;
use Swoft\Di\BeanFactory;
use Swoft\Event\Event;

/**
 *
 *
 * @uses      InitApplicationContext
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class InitApplicationContext
{
    public function init()
    {
        $this->registerListeners();
        $this->applicationLoader();
        $this->autoloadRoutes();
    }
    private function registerListeners()
    {
        // 监听器注册
        $listeners = BeanFactory::getResourceDataProxy()->listeners;
        ApplicationContext::registerListeners($listeners);
    }

    private function applicationLoader()
    {
        // 应用初始化加载事件
        $resourceDataProxy = BeanFactory::getResourceDataProxy();
        App::trigger(Event::APPLICATION_LOADER, null, $resourceDataProxy);
    }

    private function autoloadRoutes()
    {
        // 重新加载路由
        require_once BASE_PATH . '/app/routes.php';
    }
}

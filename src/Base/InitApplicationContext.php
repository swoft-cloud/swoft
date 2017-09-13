<?php

namespace Swoft\Base;

use Swoft\App;
use Swoft\Bean\Collector;
use Swoft\Event\Event;

/**
 * 应用初始化
 *
 * @uses      InitApplicationContext
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class InitApplicationContext
{
    /**
     * 初始化
     */
    public function init()
    {
        // 注册监听器
        $this->registerListeners();
        // 初始化时间
        $this->applicationLoader();
        // 路由加载
        $this->autoloadRoutes();
    }

    /**
     * 注册监听器
     */
    private function registerListeners()
    {
        // 监听器注册
        $listeners = Collector::$listeners;
        ApplicationContext::registerListeners($listeners);
    }

    /**
     * 初始化事件
     */
    private function applicationLoader()
    {
        // 应用初始化加载事件
        App::trigger(Event::APPLICATION_LOADER, null);
    }

    /**
     * 重新加载路由
     */
    private function autoloadRoutes()
    {
        require_once BASE_PATH . '/app/routes.php';
    }
}

<?php

namespace Swoft\Event\Listeners;

use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Di\Annotation\Listener;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\IApplicationListener;
use Swoft\Event\Event;

/**
 * 请求后事件
 *
 * @Listener(Event::AFTER_REQUEST)
 * @uses      AfterRequestListener
 * @version   2017年08月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AfterRequestListener implements IApplicationListener
{
    /**
     * 事件回调
     *
     * @param ApplicationEvent|null $event      事件对象
     * @param array                 ...$params  事件附加信息
     */
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params)
    {
        App::getLogger()->appendNoticeLog();
        RequestContext::destory();
    }
}

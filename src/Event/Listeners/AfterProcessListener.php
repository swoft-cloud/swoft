<?php

namespace Swoft\Event\Listeners;

use Swoft\App;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\Event;
use Swoft\Event\IApplicationListener;

/**
 *
 * @Listener(Event::AFTER_PROCESS)
 * @uses      AfterProcessListener
 * @version   2017年10月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AfterProcessListener implements IApplicationListener
{
    /**
     * 事件回调
     *
     * @param ApplicationEvent|null $event     事件对象
     * @param array                 ...$params 事件附加信息
     */
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params)
    {
        // 日志初始化
        App::getLogger()->appendNoticeLog(true);
    }
}
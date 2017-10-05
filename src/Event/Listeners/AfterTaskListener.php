<?php

namespace Swoft\Event\Listeners;

use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\Event;
use Swoft\Event\IApplicationListener;
use Swoft\Task\Task;

/**
 * 任务后置事件
 *
 * @Listener(Event::AFTER_TASK)
 * @uses      AfterTaskListener
 * @version   2017年09月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AfterTaskListener implements IApplicationListener
{
    /**
     * 事件回调
     *
     * @param ApplicationEvent|null $event      事件对象
     * @param array                 ...$params  事件附加信息
     */
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params)
    {
        if(count($params) <= 0){
            return ;
        }

        $type = $params[0];

        App::getLogger()->appendNoticeLog(true);
        if($type != Task::TYPE_CRON){
            RequestContext::destory();
        }
    }
}
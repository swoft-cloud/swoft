<?php

namespace Swoft\Event\Listeners;

use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\Event;
use Swoft\Event\Events\BeforeTaskEvent;
use Swoft\Event\IApplicationListener;

/**
 * 任务前置事件
 *
 * @Listener(Event::BEFORE_TASK)
 * @uses      BeforeTaskListener
 * @version   2017年09月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeforeTaskListener implements IApplicationListener
{
    /**
     * 事件回调
     *
     * @param ApplicationEvent|null $event      事件对象
     * @param array                 ...$params  事件附加信息
     */
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params)
    {
        /* @var BeforeTaskEvent $beforeEvent*/
        $beforeEvent = $event;

        $logid = $beforeEvent->getLogid();
        $spanid = $beforeEvent->getSpanid();
        $method = $beforeEvent->getMethod();
        $taskName = $beforeEvent->getTaskName();
        $uri = 'task.'.$taskName.'.'.$method;

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true)
        ];
        RequestContext::setContextData($contextData);

        // 日志初始化
        App::getLogger()->initialize();

        // 连接池初始化
    }
}
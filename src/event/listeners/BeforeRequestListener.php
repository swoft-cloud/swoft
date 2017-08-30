<?php

namespace swoft\event\listeners;

use swoft\base\RequestContext;
use swoft\di\annotation\Listener;
use swoft\event\ApplicationEvent;
use swoft\event\IApplicationListener;
use swoft\event\Event;

/**
 * 请求前
 *
 * @Listener(Event::BEFORE_REQUEST)
 * @uses      BeforeRequestListener
 * @version   2017年08月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeforeRequestListener implements IApplicationListener
{
    public function onApplicationEvent(ApplicationEvent $event = null)
    {
        // header获取日志ID和spanid请求跨度ID
        $logid = RequestContext::getRequest()->getHeader('logid', uniqid());
        $spanid = RequestContext::getRequest()->getHeader('spanid', 0);
        $uri = RequestContext::getRequest()->getRequestUri();

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true),
        ];
        RequestContext::setContextData($contextData);
    }
}
<?php

namespace Swoft\Event\Listeners;

use Swoft\Base\RequestContext;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\IApplicationListener;
use Swoft\Event\Event;

/**
 * rpc请求处理之前事件
 *
 *
 * @Listener(Event::BEFORE_RECEIVE)
 * @uses      BeforeReceiveListener
 * @version   2017年08月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeforeReceiveListener implements IApplicationListener
{

    /**
     * 事件回调
     *
     * @param ApplicationEvent|null $event      事件对象
     * @param array                 ...$params  事件附加信息
     */
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params)
    {
        if (!isset($params[0])) {
            return ;
        }

        $data = $params[0];
        $logid = $data['logid'] ?? uniqid();
        $spanid = $data['spanid'] ?? 0;
        $uri = $data['func'] ?? "null";

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true),
        ];
        RequestContext::setContextData($contextData);
    }
}

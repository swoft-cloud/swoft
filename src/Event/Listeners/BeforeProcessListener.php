<?php

namespace Swoft\Event\Listeners;

use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\IApplicationListener;
use Swoft\Bean\Annotation\Listener;
use Swoft\Event\Event;
use Swoole\Process;

/**
 *
 * @Listener(Event::BEFORE_PROCESS)
 * @uses      BeforeProcessListener
 * @version   2017年10月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeforeProcessListener implements IApplicationListener
{
    /**
     * 事件回调
     *
     * @param ApplicationEvent|null $event     事件对象
     * @param array                 ...$params 事件附加信息
     */
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params)
    {
        if (!isset($params[0]) || !isset($params[1])) {
            return;
        }

        $logid = uniqid();
        $spanid = 0;
        /* @var Process $process */
        $process = $params[1];
        $processName = $params[0];
        $processPid = $process->pid;
        $uri = 'process-' . $processName;

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true)
        ];

        \Swoft\Process\Process::setId($processPid);
        RequestContext::setContextData($contextData);

        // 日志初始化
        App::getLogger()->initialize();
    }
}
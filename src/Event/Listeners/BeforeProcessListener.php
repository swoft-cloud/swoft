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
 * 进程开始事件
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
        if (count($params) < 3) {
            return;
        }

        $spanid = 0;
        $logid = uniqid();

        /* @var Process $process */
        $log = $params[2];
        $process = $params[1];
        $processName = $params[0];
        $processPid = $process->pid;
        $uri = 'process-' . $processName;
        $flushInterval = $log['flushInterval'];

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true)
        ];

        \Swoft\Process\Process::setId($processPid);
        App::getLogger()->setFlushInterval($flushInterval);
        RequestContext::setContextData($contextData);

        // 日志初始化
        App::getLogger()->initialize();
    }
}
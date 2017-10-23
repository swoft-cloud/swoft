<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Scheduled;
use Swoft\Bean\Annotation\Task;
use Swoft\Bean\Collector;

/**
 * ScheduledParser注解解析
 *
 * @uses      ScheduledParser
 * @version   2017年09月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ScheduledParser extends AbstractParser
{
    /**
     * ScheduledParser注解解析
     *
     * @param string    $className
     * @param Scheduled $objectAnnotation
     * @param string    $propertyName
     * @param string    $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        if (!isset(Collector::$crontab[$className])) {
            return;
        }

        // 定时任务数据收集
        $cron = $objectAnnotation->getCron();
        $taskName = Collector::$crontab[$className]['task'];
        $task = [
            'cron'   => $cron,
            'task'   => $taskName,
            'method' => $methodName,
            'type'   => \Swoft\Task\Task::TYPE_CRON,
        ];
        Collector::$crontab[$className]['crons'][] = $task;
    }
}
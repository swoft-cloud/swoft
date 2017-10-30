<?php

namespace Swoft\Process;

use Swoft\App;
use Swoft\Crontab\Crontab;
use Swoole\Process;

/**
 * Crontab执行进程
 * @uses      CronExecProcess
 * @version   2017年10月22日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CronExecProcess extends AbstractProcess
{
    /**
     * @param Process $process
     */
    public function run(Process $process)
    {
        $process->name($this->server->getPname() . " my process ");
        /* @var Crontab $cron */
        $cron = App::getBean('crontab');

        // Swoole/HttpServer
        $server = $this->server->getServer();

        $server->tick(0.5 * 1000, function () use ($cron) {
            $tasks = $cron->getExecTasks();
            if (!empty($tasks)) {
                foreach ($tasks as $task) {
                    // 投递任务
                    $this->task($task['taskClass'], $task['taskMethod']);
                    $cron->finishTask($task['key']);
                }
            }
        });
    }
}

<?php

namespace App\Process;

use Swoft\App;
use Swoft\Process\AbstractProcess;
use Swoole\Process;
use Swoft\Task\Task;

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
        // Crontab对象
        $crontabObject = App::getCrontab();
        // Swoole/HttpServer
        $server = $this->server->getServer();

        $server->tick(0.5 * 1000, function () use ($crontabObject) {
            $tasks = $crontabObject->getExecTasks();
            if (!empty($tasks)) {
                    foreach ($tasks as $task) {
                        // 投递任务
                        $result = Task::run('test', 'cronTask', []);
                        $crontabObject->finishTask($task['key']);
                    }
            }
        });
    }
}

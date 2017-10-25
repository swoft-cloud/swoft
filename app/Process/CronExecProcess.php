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
        $crontab = App::getCrontab();
        $server = $this->server->getServer();
        $server->tick(0.5 * 1000, function () use ($crontab) {
            $tasks = $crontab->getExecTasks();
            if (!empty($tasks)) {
                    foreach ($tasks as $task) {
                        $result = Task::run('test', 'cronTask', []);
                        $crontab->finishTask($task['key']);
                    }
            }
        });
    }
}

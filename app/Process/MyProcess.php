<?php

namespace App\Process;

use Swoft\App;
use Swoft\Process\AbstractProcess;
use Swoole\Process;

/**
 * 自定义进程demo
 *
 * @uses      MyProcess
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MyProcess extends AbstractProcess
{
    /**
     * 实际进程运行逻辑
     *
     * @param Process $process 进程对象
     */
    public function run(Process $process)
    {
        $pname = $this->server->getPname();
        $processName = "$pname myProcess process";
        $process->name($processName);

        $i = 1;
        while (true) {

            $this->task('test', 'testRpc', [], 5);
            echo "this my process \n";
            App::trace("my process count=" . $i);
            sleep(10);
            $i++;
        }
    }
}
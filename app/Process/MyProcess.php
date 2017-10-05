<?php

namespace App\Process;

use Swoft\App;
use Swoft\Base\Context;
use Swoft\Bean\Annotation\AutoProcess;
use Swoft\Bean\Annotation\Log;
use Swoft\Process\IProcess;
use Swoole\Process;

/**
 *
 * @AutoProcess(name="myProcess")
 * @Log(5)
 * @uses      MyProcess
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MyProcess implements IProcess
{
    /**
     * @param Process $process
     * @param string  $processPrefix
     */
    public function run(Process $process, string $processPrefix)
    {
        $process->name($processPrefix." my process ");
        $i = 0;
        while (true) {
            App::profileStart("sleep");
            echo "this is my process2 ................\n". Context::getStatus();
            App::trace("this is trace");
            App::info("this is trace");
            sleep(2);
            $i++;

            App::profileEnd("sleep");
            if($i == 3){
                break;
            }
        }
    }
}
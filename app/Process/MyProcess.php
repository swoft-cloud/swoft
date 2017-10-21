<?php

namespace App\Process;

use Swoft\App;
use Swoft\Process\AbstractProcess;
use Swoft\Server\AbstractServer;
use Swoole\Process;

/**
 *
 * @uses      MyProcess
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MyProcess extends AbstractProcess
{
    public function run(AbstractServer $server,Process $process)
    {
        $pname = $server->getPname();
        $processName = "$pname myProcess process";
        $process->name($processName);

        $i = 1;
        while (true){
            echo "this my process \n";
            App::trace("my process count=".$i);
            sleep(10);
            $i++;
        }
    }
}
<?php

namespace App\Process;

use Swoft\Process\IProcess;
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
class MyProcess implements IProcess
{
    public function run(AbstractServer $server,Process $process, string $processPrefix)
    {

    }
}
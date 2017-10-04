<?php

namespace Swoft\Process;

use Swoole\Process;

/**
 *
 *
 * @uses      IProcess
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IProcess
{
    public function run(Process $process, string $processPrefix);
}
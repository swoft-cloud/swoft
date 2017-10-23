<?php

namespace Swoft\Process;

use Swoft\Server\AbstractServer;
use Swoole\Process;

/**
 * 进程接口
 *
 * @uses      IProcess
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IProcess
{
    /**
     * 进程启动前准备工作是否完成
     *
     * @return bool
     */
    public function isReady();

    /**
     * 运行进程程序
     *
     * @param Process        $process
     */
    public function run(Process $process);
}
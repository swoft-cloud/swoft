<?php

namespace Swoft\Process;

use Swoft\App;
use Swoft\Base\Inotify;
use Swoft\Server\AbstractServer;
use Swoole\Process;

/**
 * reload进程
 *
 * @uses      ReloadProcess
 * @version   2017年10月21日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ReloadProcess extends AbstractProcess
{
    /**
     * 运行进程逻辑
     *
     * @param Process        $process
     */
    public function run( Process $process)
    {
        $pname = $this->server->getPname();
        $processName = "$pname reload process";
        $process->name($processName);

        /* @var Inotify $inotify */
        $inotify = App::getBean('inotify');
        $inotify->setServer($this->server);
        $inotify->run();
    }

    /**
     * 进程启动准备工作
     *
     * @return bool
     */
    public function isReady()
    {
        if (!AUTO_RELOAD || !extension_loaded('inotify')) {
            echo "自动reload未开启，请检查配置(AUTO_RELOAD)和inotify扩展是否安装正确! \n";
            return false;
        }
        return true;
    }
}
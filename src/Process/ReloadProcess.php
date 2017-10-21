<?php

namespace Swoft\Process;

use Swoft\App;
use Swoft\Base\Coroutine;
use Swoft\Base\Inotify;
use Swoft\Server\AbstractServer;
use Swoole\Process;

/**
 *
 *
 * @uses      ReloadProcess
 * @version   2017年10月21日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ReloadProcess extends AbstractProcess
{
    public function run(AbstractServer $server, Process $process)
    {
        $pname = $server->getPname();
        $processName = "$pname reload process";
        $process->name($processName);

        /* @var Inotify $inotify */
        $inotify = App::getBean('inotify');
        $inotify->setServer($server);
        $inotify->run();
    }

    public function isReady()
    {
        if (!AUTO_RELOAD || !extension_loaded('inotify')) {
            echo "自动reload未开启，请检查配置(AUTO_RELOAD)和inotify扩展是否安装正确! \n";
            return false;
        }
        return true;
    }
}
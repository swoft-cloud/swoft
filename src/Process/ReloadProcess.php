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
    public function run(AbstractServer $server, Process $process, string $processPrefix)
    {
        $process->name('php-swoft reload process');

        /* @var Inotify $inotify*/
        $inotify = App::getBean('inotify');
        $inotify->setServer($server);
        $inotify->run();
    }
}
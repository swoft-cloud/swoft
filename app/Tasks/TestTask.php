<?php

namespace App\Tasks;

use Swoft\Bean\Annotation\Scheduled;
use Swoft\Bean\Annotation\Task;

/**
 *
 *
 * @uses      TestTask
 * @Task("test")
 * @version   2017年09月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class TestTask
{
    public function corTask($p1, $p2)
    {
        echo "this cor task \n";
        return 'cor'." $p1". " $p2";
    }

    public function asyncTask()
    {
        echo "this async task \n";
        return 'async';
    }

    /**
     * @Scheduled(cron="0 0/1 8-20 * * ?")
     */
    public function cronTask()
    {
        echo "this cron task  \n";
        return 'cron';
    }
}
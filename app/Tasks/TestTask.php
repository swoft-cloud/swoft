<?php

namespace App\Tasks;

use Swoft\App;
use Swoft\Bean\Annotation\Scheduled;
use Swoft\Bean\Annotation\Task;

/**
 * 测试task
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
    /**
     * 协程task
     *
     * @param mixed $p1
     * @param mixed $p2
     *
     * @return string
     */
    public function corTask($p1, $p2)
    {
        static $status = 1;
        $status++;
        echo "this cor task \n";
        App::trace("this is task log");
        return 'cor' . " $p1" . " $p2 " . $status;
    }

    /**
     * 异步task
     *
     * @return string
     */
    public function asyncTask()
    {
        static $status = 1;
        $status++;
        echo "this async task \n";
        App::trace("this is task log");
        return 'async-' . $status;
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
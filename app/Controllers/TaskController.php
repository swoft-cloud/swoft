<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Controllers;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Task\Task;

/**
 * @Controller("task")
 */
class TaskController
{
    /**
     * @return array
     */
    public function batch()
    {
        $count = 0;
        $result = [];
        while ($count < 50000){
            $result[] = Task::deliver('sync', 'batchTask', [], Task::TYPE_ASYNC);
            $count++;
        }

        return $result;
    }


    /**
     * Deliver co task
     *
     * @return array
     */
    public function co()
    {
        $result  = Task::deliver('sync', 'deliverCo', ['p', 'p2'], Task::TYPE_CO);

        return [$result];
    }

    /**
     * Deliver async task
     *
     * @return array
     */
    public function async()
    {
        $result  = Task::deliver('sync', 'deliverAsync', ['p', 'p2'], Task::TYPE_ASYNC);

        return [$result];
    }

    /**
     * Cache task
     *
     * @return array
     */
    public function cache()
    {
        $result  = Task::deliver('sync', 'cache', [], Task::TYPE_CO);

        return [$result];
    }

    /**
     * Mysql task
     *
     * @return array
     */
    public function mysql()
    {
        $result  = Task::deliver('sync', 'mysql', [], Task::TYPE_CO);

        return [$result];
    }

    /**
     * Http task
     *
     * @return array
     */
    public function http()
    {
        $result  = Task::deliver('sync', 'http', [], Task::TYPE_CO);

        return [$result];
    }

    /**
     * Rpc task
     *
     * @return array
     */
    public function rpc()
    {
        $result  = Task::deliver('sync', 'rpc', [], Task::TYPE_CO);

        return [$result];
    }

    /**
     * Rpc task
     *
     * @return array
     */
    public function rpc2()
    {
        $result  = Task::deliver('sync', 'rpc2', [], Task::TYPE_CO);

        return [$result];
    }
}
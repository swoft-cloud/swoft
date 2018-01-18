<?php

namespace App\Controllers;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Task\Task;

/**
 * @Controller("task")
 * @uses      TaskController
 * @version   2018年01月13日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class TaskController
{
    public function cor()
    {
        $result  = Task::deliver('test', 'corTask', ['params1', 'params2'], Task::TYPE_COR);
        return [$result, 1];
    }
}
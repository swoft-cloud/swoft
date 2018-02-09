<?php

namespace App\Controllers;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Task\Task;

/**
 * @Controller("task")
 */
class TaskController
{
    public function cor()
    {
        $result  = Task::deliver('test', 'corTask', ['params1', 'params2'], Task::TYPE_COR);
        return [$result, 1];
    }
}
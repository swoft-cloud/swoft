<?php

namespace App\Listener;

use Swoft\Bean\Annotation\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Task\Event\TaskEvent;

/**
 * Task finish handler
 *
 * @Listener(TaskEvent::FINISH_TASK)
 */
class TaskFinish implements EventHandlerInterface
{
    /**
     * @param \Swoft\Event\EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        var_dump("task finish! ", $event->getParams());
    }
}
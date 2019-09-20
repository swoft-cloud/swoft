<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Listener\Test;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Exception\SwoftException;
use Swoft\Log\Helper\CLog;
use Swoft\Server\SwooleEvent;

/**
 * Class WorkerStopListener
 *
 * @since 2.0
 *
 * @Listener(SwooleEvent::WORKER_STOP)
 */
class WorkerStopListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     *
     * @throws SwoftException
     */
    public function handle(EventInterface $event): void
    {
        $context = context();

        CLog::debug('Worker Start context=' . get_class($context));
    }
}

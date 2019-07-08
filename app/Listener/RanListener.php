<?php declare(strict_types=1);


namespace App\Listener;

use Swoft\Db\Connection\Connection;
use Swoft\Db\DbEvent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class RanListener
 *
 * @since 2.0
 *
 * @Listener(DbEvent::SQL_RAN)
 */
class RanListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {

        /* @var Connection $connection */
        $connection = $event->getTarget();

        $querySql    = $event->getParam(0);
        $bindings = $event->getParam(1);
    }
}

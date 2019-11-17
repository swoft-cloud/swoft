<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
class DbRanListener implements EventHandlerInterface
{
    /**
     * SQL ran
     *
     * @param EventInterface $event
     *
     */
    public function handle(EventInterface $event): void
    {
        /** @var Connection $connection */
        $connection = $event->getTarget();

        $querySql = $event->getParam(0);
        $bindings = $event->getParam(1);

        $rawSql = $connection->getRawSql($querySql, $bindings);
        // output()->info($rawSql);
    }
}

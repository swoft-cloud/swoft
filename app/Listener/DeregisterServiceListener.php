<?php declare(strict_types=1);


namespace App\Listener;


use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Consul\Agent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Server\Swoole\SwooleEvent;

/**
 * Class DeregisterServiceListener
 *
 * @since 2.0
 *
 * @Listener(SwooleEvent::SHUTDOWN)
 */
class DeregisterServiceListener implements EventHandlerInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /* @var HttpServer $httpServer */
        $httpServer = $event->getTarget();

//        $scheduler = Swoole\Coroutine\Scheduler();
//        $scheduler->add(function () use ($httpServer) {
//            $this->agent->deregisterService('swoft');
//        });
//        $scheduler->start();
    }
}
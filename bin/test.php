<?php

namespace Swoft\Tcp\Server\Swoole;

use Co\Server as CoServer;

class ConnectListener_5c46c8f69d793 extends \Swoft\Tcp\Server\Swoole\ConnectListener
{
    use \Swoft\Aop\AopTrait;
    public function onConnect(CoServer $server, int $fd, int $reactorId) : void
    {
        return $this->__proxyCall('Swoft\\Tcp\\Server\\Swoole\\ConnectListener', 'onConnect', func_get_args());
    }
}



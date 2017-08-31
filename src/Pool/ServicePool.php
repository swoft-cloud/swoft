<?php

namespace Swoft\Pool;

use Swoft\App;

/**
 * RPC服务连接池
 *
 * @uses      ServicePool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServicePool extends ConnectPool
{
    /**
     * 创建连接
     *
     * @return null|\Swoole\Coroutine\Client
     */
    public function createConnect()
    {
        $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);

        $address = $this->getConnectAddress();
        list($host, $port) = explode(":", $address);
        if (!$client->connect($host, $port, $this->timeout))
        {
            App::error("Service connect fail errorCode=".$client->errCode." host=".$host." port=".$port);
            return null;
        }

        return $client;
    }

    public function reConnect($client)
    {
        list($host, $port) = $this->getConnectAddress();
    }
}
<?php

namespace swoft\pool;

use swoft\App;

/**
 *
 *
 * @uses      ServicePool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServicePool extends ConnectPool
{
    public function createConnect()
    {
        $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);

        list($host, $port) = $this->getConnectInfo();
        if (!$client->connect($host, $port, $this->timeout))
        {
            App::error("service connect fail errorCode=".$client->errCode." host=".$host." port=".$port);
            return null;
        }

        return $client;
    }

    public function reConnect($client)
    {
        list($host, $port) = $this->getConnectInfo();
    }

    public function getConnectInfo(){
        return [
            "127.0.0.1",
            8099
        ];
    }
}
<?php

namespace swoft\pool;

/**
 *
 *
 * @uses      RedisPool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisPool extends ConnectPool
{
    public function createConnect()
    {
        list($host, $port) = $this->getConnectInfo();
        $redis = new \Swoole\Coroutine\Redis();
        $redis->connect($host, $port);
        return $redis;
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
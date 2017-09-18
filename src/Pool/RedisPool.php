<?php

namespace Swoft\Pool;

use Swoft\App;

/**
 * redis连接池
 *
 * @uses      RedisPool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisPool extends ConnectPool
{
    /**
     * 创建一个连接
     *
     * @return \Swoole\Coroutine\Redis
     */
    public function createConnect()
    {
        $address = $this->getConnectAddress();
        list($host, $port) = explode(":", $address);

        /* @var \Redis $redis*/
        $redis = new \Swoole\Coroutine\Redis();
        $result = $redis->connect($host, $port, $this->timeout);
        if ($result == false) {
            App::error("redis连接失败，host=".$host." port=".$port." timeout=".$this->timeout);
            return null;
        }
        return $redis;
    }

    public function reConnect($client)
    {
        list($host, $port) = $this->getConnectInfo();
    }
}

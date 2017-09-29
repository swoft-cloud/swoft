<?php

namespace Swoft\Pool;

use Swoft\App;
use Swoft\Redis\Cache\RedisConnect;
use Swoft\Redis\Cache\SyncRedisConnect;

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
     * @return RedisConnect|SyncRedisConnect
     */
    public function createConnect()
    {
        if (App::isWorkerStatus()) {
            return new RedisConnect($this);
        }
        return new SyncRedisConnect($this);
    }

    public function reConnect($client)
    {
        list($host, $port) = $this->getConnectInfo();
    }
}

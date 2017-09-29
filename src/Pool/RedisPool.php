<?php

namespace Swoft\Pool;

use Swoft\Cache\RedisConnect;

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
     * @return RedisConnect
     */
    public function createConnect()
    {
        return new RedisConnect($this);
    }

    public function reConnect($client)
    {
        list($host, $port) = $this->getConnectInfo();
    }
}

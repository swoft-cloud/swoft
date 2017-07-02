<?php

namespace swoft\connect;

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
    public $host = '127.0.0.1';
    public $port = 63379;

    public function createConnect()
    {
        $redis = new Swoole\Coroutine\Redis();
        $redis->connect($this->host, $this->port);
        return $redis;
    }
}
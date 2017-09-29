<?php

namespace Swoft\Redis\Cache;

use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnect;

/**
 *
 *
 * @uses      SyncRedisConnect
 * @version   2017年09月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SyncRedisConnect extends AbstractConnect
{
    /**
     * @var \Redis
     */
    protected $connect;

    public function createConnect()
    {
        $timeout = $this->connectPool->getTimeout();
        $address = $this->connectPool->getConnectAddress();
        list($host, $port) = explode(":", $address);

        $redis = new \Redis();
        $redis->connect($host, $port, $timeout);

        $this->connect = $redis;
    }

    public function reConnect()
    {
    }

    public function __call($method, $arguments)
    {
        return PhpHelper::call([$this->connect, $method], $arguments);
    }
}
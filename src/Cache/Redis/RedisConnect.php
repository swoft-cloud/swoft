<?php

namespace Swoft\Redis\Cache;

use Swoft\App;
use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnect;
use Swoole\Coroutine\Redis;

/**
 *
 *
 * @uses      RedisConnect
 * @version   2017年09月28日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisConnect extends AbstractConnect
{
    /**
     * @var Redis
     */
    protected $connect;

    public function createConnect()
    {
        $timeout = $this->connectPool->getTimeout();
        $address = $this->connectPool->getConnectAddress();
        list($host, $port) = explode(":", $address);

        $redis = new Redis();
        $result = $redis->connect($host, $port, $timeout);
        if ($result == false) {
            App::error("redis连接失败，host=".$host." port=".$port." timeout=".$this->timeout);
            return null;
        }

        $this->connect = $redis;
    }

    public function reConnect()
    {

    }

    public function setDefer($defer = true)
    {
        $this->connect->setDefer($defer);
    }

    public function __call($method, $arguments)
    {
        return PhpHelper::call([$this->connect, $method], $arguments);
    }
}
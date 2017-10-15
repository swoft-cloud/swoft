<?php

namespace Swoft\Redis\Cache;

use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnect;

/**
 * 同步redis连接
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
     * redis连接
     *
     * @var \Redis
     */
    protected $connect;

    /**
     * 创建连接
     */
    public function createConnect()
    {
        // 连接信息
        $timeout = $this->connectPool->getTimeout();
        $address = $this->connectPool->getConnectAddress();
        list($host, $port) = explode(":", $address);

        // 初始化连接
        $redis = new \Redis();
        $redis->connect($host, $port, $timeout);
        $this->connect = $redis;
    }

    /**
     * 重连
     */
    public function reConnect()
    {
    }

    /**
     * 魔术方法，实现调用转移
     *
     * @param string $method    方面名称
     * @param array  $arguments 参数
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return PhpHelper::call([$this->connect, $method], $arguments);
    }
}
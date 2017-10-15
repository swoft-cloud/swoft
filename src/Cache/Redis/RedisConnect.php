<?php

namespace Swoft\Redis\Cache;

use Swoft\App;
use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnect;
use Swoole\Coroutine\Redis;

/**
 * 协程Redis连接
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

    /**
     * 创建连接
     */
    public function createConnect()
    {
        // 连接信息
        $timeout = $this->connectPool->getTimeout();
        $address = $this->connectPool->getConnectAddress();
        list($host, $port) = explode(":", $address);

        // 创建连接
        $redis = new Redis();
        $result = $redis->connect($host, $port, $timeout);
        if ($result == false) {
            App::error("redis连接失败，host=" . $host . " port=" . $port . " timeout=" . $timeout);
            return;
        }

        $this->connect = $redis;
    }

    /**
     * 重连
     */
    public function reConnect()
    {

    }

    /**
     * 设置延迟收包
     *
     * @param bool $defer
     */
    public function setDefer($defer = true)
    {
        $this->connect->setDefer($defer);
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
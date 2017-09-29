<?php

namespace Swoft\Cache;

use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Exception\RedisException;

/**
 * redis客户端封装
 *
 * @uses      Redis
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 *
 * @method static int del( $key1, $key2 = null, $key3 = null )
 * @method static bool exists( $key )
 * @method static bool expire( $key, $ttl )
 * @method static int ttl( $key )
 * @method static string|bool get($key)
 * @method static bool set($key, $value, $timeout = 0)
 * @method static int bitCount( $key )
 * @method static int decr( $key )
 * @method static int decrBy( $key, $value )
 * @method static int incr( $key )
 * @method static int incrBy( $key, $value )
 * @method static array mget( array $array )
 * @method static bool mset( array $array )
 * @method static bool setnx( $key, $value )
 */
class RedisClient
{
    /**
     * 服务名称
     */
    const SERVICE_NAME = "redisPool";

    /**
     * 目前支持redis操作方法的集合,若需新方法支持，添加到里面即可。
     */
    const redis_operations = [
            // keys
            'del', 'exists', 'expire', 'ttl',

            // string
            'get', 'set', 'bitCount', 'decr', 'decrBy', 'incr', 'incrBy', 'mget', 'mset', 'setnx'

            // lists

            // sets

            // hash

            // zset

        ];

    /**
     * 非延迟调用
     *
     * @param string $method    方法名称
     * @param array  $params    参数
     *
     * @return mixed
     */
    public static function call(string $method, array $params)
    {
        $profileKey = self::getRedisProfile($method);
        $connectPool = App::getBean(self::SERVICE_NAME);

        /* @var $client RedisConnect */
        $client = $connectPool->getConnect();
        App::profileStart($profileKey);
        $result = $client->$method(...$params);
        App::profileEnd($profileKey);
        $connectPool->release($client);

        return $result;
    }

    /**
     * 延迟收包调用
     *
     * @param string $method 方法名称
     * @param array  $params 参数
     *
     * @return RedisResult
     */
    public static function deferCall(string $method, array $params)
    {
        $profileKey = self::getRedisProfile($method);
        $connectPool = App::getBean(self::SERVICE_NAME);

        /* @var $client RedisConnect */
        $client = $connectPool->getConnect();
        $client->setDefer();
        $result = $client->$method(...$params);

        return new RedisResult($connectPool, $client, $profileKey, $result);
    }

    /**
     * 魔术方法执行调用
     *
     * @param string $method    方法名称
     * @param array  $arguments 参数
     *
     * @return mixed
     * @throws RedisException
     */
    public static function __callStatic($method, $arguments)
    {
        if (!in_array($method, self::redis_operations)) {
            App::error("目前不支持redis该方法调用,method".$method." args=".json_encode($arguments));
            throw new RedisException("目前不支持redis该方法调用,method".$method." args=".json_encode($arguments));
        }
        return self::call($method, $arguments);
    }

    /**
     * redis缓存性能统计key
     *
     * @param string $method 方法名称
     *
     * @return string
     */
    private static function getRedisProfile(string $method)
    {
        return self::SERVICE_NAME . "." . $method;
    }
}

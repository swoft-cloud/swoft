<?php
namespace swoft\cache;

use swoft\App;
use swoft\base\ApplicationContext;

/**
 *
 *
 * @uses      Redis
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 *
 * @method static string|bool get($key)
 * @method static bool set( $key, $value, $timeout = 0 )
 */
class RedisClient
{
    const SERVICE_NAME = "redisPool";

    const redis_operations = [
        // string
        'get', 'set'
    ];

    public static function call(string $method, array $params)
    {
        $profileKey = self::SERVICE_NAME.".".$method;
        $connectPool = ApplicationContext::getBean(self::SERVICE_NAME);

        /* @var $client \Swoole\Coroutine\Redis */
        $client = $connectPool->getConnect();
        App::profileStart($profileKey);
        $result = $client->$method(...$params);
        App::profileEnd($profileKey);
        $connectPool->release($client);

        return $result;
    }

    public static function deferCall(string $method, array $params)
    {
        $profileKey = self::SERVICE_NAME.".".$method;
        $connectPool = ApplicationContext::getBean(self::SERVICE_NAME);

        /* @var $client \Swoole\Coroutine\Redis */
        $client = $connectPool->getConnect();
        $client->setDefer();
        $result = $client->$method(...$params);

        return new RedisResult($connectPool, $client, $profileKey, $result);
    }

    public static function __callStatic($method, $arguments)
    {
        if(!in_array($method, self::redis_operations)){

        }
        return self::call($method, $arguments);
    }
}
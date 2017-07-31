<?php

namespace swoft;

use swoft\base\ApplicationContext;
use swoft\base\Timer;
use swoft\circuit\CircuitBreakerManager;
use swoft\log\Logger;
use swoft\service\IPack;
use swoft\service\ServiceProvider;
use swoft\web\Application;

/**
 *
 *
 * @uses      App
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class App
{
    /**
     * @var Application
     */
    public static $app;

    public static $properties;

    public static function getMysqlPool()
    {
        return self::getBean('mysql');
    }

    public static function getRedisPool()
    {
        return self::getBean('redisPool');
    }

    public static function setProperties($properties = null)
    {
        if($properties == null){
            $properties = self::getProperties();
        }
        self::$properties = $properties;
    }

    public static function getBean($name){
        return ApplicationContext::getBean($name);
    }

    public static function getProperties()
    {
        return ApplicationContext::getBean('config');
    }

    /**
     * @return ServiceProvider
     */
    public static function getServiceProvider()
    {
        return ApplicationContext::getBean('serviceProvider');
    }

    /**
     * @return Logger
     */
    public static function getLogger()
    {
        return ApplicationContext::getBean('logger');
    }

    /**
     * @return IPack
     */
    public static function getPacker()
    {
        return ApplicationContext::getBean('packer');
    }

    /**
     * @return Timer
     */
    public static function getTimer()
    {
        return ApplicationContext::getBean('timer');
    }

    public static function trace($message, array $context = array())
    {
        self::getLogger()->addTrace($message,$context);
    }

    public static function error($message, array $context = array())
    {
        self::getLogger()->error($message, $context);
    }

    public static function info($message, array $context = array())
    {
        self::getLogger()->info($message, $context);
    }

    public static function warning($message, array $context = array())
    {
        self::getLogger()->warning($message, $context);
    }

    public static function debug($message, array $context = array())
    {
        self::getLogger()->debug($message, $context);
    }

    public static function pushlog($key, $val)
    {
        self::getLogger()->pushLog($key, $val);
    }

    public static function profileStart($name)
    {
        self::getLogger()->profileStart($name);
    }

    public static function profileEnd($name)
    {
        self::getLogger()->profileEnd($name);
    }

    public static function getCoroutineId(){
        return \Swoole\Coroutine::getuid();
    }

    public static function counting($name, $hit, $total = null)
    {
        self::getLogger()->counting($name, $hit, $total);
    }
}
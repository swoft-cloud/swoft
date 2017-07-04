<?php

namespace swoft;

use swoft\base\ApplicationContext;
use swoft\circuit\CircuitBreakerManager;
use swoft\pool\ManagerPool;
use swoft\web\Application;

/**
 *
 *
 * @uses      Swf
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Swf
{
    /**
     * @var Application
     */
    public static $app;

    /**
     * @return ManagerPool
     */
    public static function getMangerPool()
    {
        return ApplicationContext::getBean("managerPool");
    }

    /**
     * @return CircuitBreakerManager
     */
    public static function getCricuitBreakerManager(){
        return ApplicationContext::getBean('circuitBreakerManager');
    }

    public static function getMysqlPool()
    {
        return self::getMangerPool()->getPool("mysql");
    }

    public static function getRedisPool()
    {
        return self::getMangerPool()->getPool("redis");
    }

    public static function trace()
    {

    }
    public static function error()
    {

    }

    public static function info()
    {

    }
}
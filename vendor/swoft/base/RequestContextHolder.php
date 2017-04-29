<?php

namespace swoft\base;

/**
 *
 *
 * @uses      RequestContextHolder
 * @version   2017年04月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestContextHolder
{
    private static $coroutineLocal;

    /**
     * @return RequestAttributes
     */
    public static function get()
    {
        $coroutineId = self::getcoroutine();
        if(!isset(self::$coroutineLocal[$coroutineId])){

        }
        return self::$coroutineLocal;
    }

    public static function set(RequestAttributes $requestAttributes)
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId] = $requestAttributes;
    }

    private static function getcoroutine()
    {
        $coroutineId = "";
        $coroutineId = md5($coroutineId);
        return $coroutineId;
    }
}
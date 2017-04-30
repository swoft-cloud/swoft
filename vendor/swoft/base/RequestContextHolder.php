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
    const COROUTINE_DATA = "data";
    const COROUTINE_REQUEST = "request";
    const COROUTINE_RESPONSE = "response";

    private static $coroutineLocal;

    /**
     * @return \Swoole\Http\Request
     */
    public static function getRequest()
    {
        return self::getCoroutineContext(self::COROUTINE_REQUEST);
    }

    /**
     * @return \Swoole\Http\Response
     */
    public static function getResponse()
    {
        return self::getCoroutineContext(self::COROUTINE_RESPONSE);
    }

    /**
     * @return array
     */
    public static function getContextData()
    {
        return self::getCoroutineContext(self::COROUTINE_DATA);
    }

    public static function set(\Swoole\Http\Request $request, \Swoole\Http\Response $response, array $contextData = [])
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId] = [
            self::COROUTINE_REQUEST => $request,
            self::COROUTINE_DATA => $contextData,
            self::COROUTINE_RESPONSE => $response,
        ];
    }

    public static function destory()
    {
        $coroutineId = self::getcoroutine();
        if(isset(self::$coroutineLocal[$coroutineId])){
            unset(self::$coroutineLocal[$coroutineId]);
        }
    }

    private function getCoroutineContext(string $name)
    {
        $coroutineId = self::getcoroutine();
        if(!isset(self::$coroutineLocal[$coroutineId])){

        }

        $coroutineContext = self::$coroutineLocal[$coroutineId];
        if(isset($coroutineContext[$name])){
            return $coroutineContext[$name];
        }
        return [];
    }

    private static function getcoroutine()
    {
        $coroutineId = \Swoole\Coroutine::getuid();
        $coroutineId = md5($coroutineId);
        return $coroutineId;
    }
}
<?php

namespace swoft\base;

use swoft\log\Logger;

/**
 *
 *
 * @uses      RequestContext
 * @version   2017年04月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestContext
{
    const COROUTINE_DATA = "data";
    const COROUTINE_REQUEST = "request";
    const COROUTINE_RESPONSE = "response";

    private static $coroutineLocal;

    /**
     * @return \swoft\web\Request
     */
    public static function getRequest()
    {
        return self::getCoroutineContext(self::COROUTINE_REQUEST);
    }

    /**
     * @return \swoft\web\Response
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

    public static function destory()
    {
        $coroutineId = self::getcoroutine();
        if(isset(self::$coroutineLocal[$coroutineId])){
            unset(self::$coroutineLocal[$coroutineId]);
        }
    }

    private static function getCoroutineContext(string $name)
    {
        $coroutineId = self::getcoroutine();
        if(!isset(self::$coroutineLocal[$coroutineId])){

        }

        $coroutineContext = self::$coroutineLocal[$coroutineId];
        if(isset($coroutineContext[$name])){
            return $coroutineContext[$name];
        }
        return null;
    }

    public static function setRequest(\Swoole\Http\Request $request)
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId][self::COROUTINE_REQUEST] = new \swoft\web\Request($request);
    }

    public static function setResponse(\Swoole\Http\Response $response)
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId][self::COROUTINE_RESPONSE] = new \swoft\web\Response($response);
    }


    public static function setContextData(array $contextData = [])
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId][self::COROUTINE_DATA] = $contextData;
    }
    private static function getcoroutine()
    {
        $coroutineId = \Swoole\Coroutine::getuid();
        $coroutineId = md5($coroutineId);
        return $coroutineId;
    }
}
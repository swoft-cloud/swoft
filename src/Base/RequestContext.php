<?php

namespace Swoft\Base;

use Swoft\App;

/**
 * 请求上下文
 *
 * @uses      RequestContext
 * @version   2017年04月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestContext
{
    /**
     * 请求数据共享区
     */
    const COROUTINE_DATA = "Data";

    /**
     * 当前请求request
     */
    const COROUTINE_REQUEST = "request";

    /**
     * 当前请求response
     */
    const COROUTINE_RESPONSE = "response";

    /**
     * @var array 协程数据保存
     */
    private static $coroutineLocal;

    /**
     * 请求request
     *
     * @return \Swoft\Web\Request
     */
    public static function getRequest()
    {
        return self::getCoroutineContext(self::COROUTINE_REQUEST);
    }

    /**
     * 请求response
     *
     * @return \Swoft\Web\Response
     */
    public static function getResponse()
    {
        return self::getCoroutineContext(self::COROUTINE_RESPONSE);
    }

    /**
     * 请求共享数据
     *
     * @return array
     */
    public static function getContextData()
    {
        return self::getCoroutineContext(self::COROUTINE_DATA);
    }

    /**
     * 初始化request
     *
     * @param \Swoole\Http\Request $request
     */
    public static function setRequest(\Swoole\Http\Request $request)
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId][self::COROUTINE_REQUEST] = new \Swoft\Web\Request($request);
    }

    /**
     * 初始化response
     *
     * @param \Swoole\Http\Response $response
     */
    public static function setResponse(\Swoole\Http\Response $response)
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId][self::COROUTINE_RESPONSE] = new \Swoft\Web\Response($response);
    }

    /**
     * 初始化数据共享
     *
     * @param array $contextData
     */
    public static function setContextData(array $contextData = [])
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId][self::COROUTINE_DATA] = $contextData;
    }

    /**
     * 设置或修改，当前请求数据共享值
     *
     * @param string $key
     * @param mixed  $val
     */
    public static function setContextDataByKey(string $key, $val)
    {
        $coroutineId = self::getcoroutine();
        self::$coroutineLocal[$coroutineId][self::COROUTINE_DATA][$key] = $val;
    }

    /**
     * 获取当前请求数据一个KEY的值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getContextDataByKey(string $key, $default = null)
    {
        $coroutineId = self::getcoroutine();
        if (isset(self::$coroutineLocal[$coroutineId][self::COROUTINE_DATA][$key])) {
            return self::$coroutineLocal[$coroutineId][self::COROUTINE_DATA][$key];
        }

        App::warning("RequestContext data数据不存在key,key=".$key);
        return $default;
    }

    /**
     * 销毁当前协程数据
     */
    public static function destory()
    {
        $coroutineId = self::getcoroutine();
        if (isset(self::$coroutineLocal[$coroutineId])) {
            unset(self::$coroutineLocal[$coroutineId]);
        }
    }

    /**
     * 获取协程上下文
     *
     * @param string $name  协程ID
     *
     * @return mixed|null
     */
    private static function getCoroutineContext(string $name)
    {
        $coroutineId = self::getcoroutine();
        if (!isset(self::$coroutineLocal[$coroutineId])) {
            App::error("协程上下文不存在，coroutineId=".$coroutineId);
            throw new \InvalidArgumentException("协程上下文不存在，coroutineId=".$coroutineId);
        }

        $coroutineContext = self::$coroutineLocal[$coroutineId];
        if (isset($coroutineContext[$name])) {
            return $coroutineContext[$name];
        }
        return null;
    }

    /**
     * 协程ID
     *
     * @return int
     */
    private static function getcoroutine()
    {
        $coroutineId = \Swoole\Coroutine::getuid();
        return $coroutineId;
    }
}

<?php

namespace swoft;

use swoft\base\ApplicationContext;
use swoft\base\Config;
use swoft\base\RequestContext;
use swoft\base\Timer;
use swoft\log\Logger;
use swoft\pool\RedisPool;
use swoft\service\ConsulProvider;
use swoft\service\IPack;
use swoft\web\Application;
use swoft\web\ErrorHandler;

/**
 * 应用简写类
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
     * @var Application 应用对象
     */
    public static $app;

    /**
     * @var Config config bean配置对象
     */
    public static $properties;

    public static function getMysqlPool()
    {
        return self::getBean('mysql');
    }

    /**
     * redis连接池
     *
     * @return RedisPool
     */
    public static function getRedisPool()
    {
        return self::getBean('redisPool');
    }

    /**
     * consul对象
     *
     * @return ConsulProvider
     */
    public static function getConsulProvider()
    {
        return self::getBean('consulProvider');
    }

    /**
     * 初始化配置对象
     *
     * @param Config $properties 容器中config对象
     */
    public static function setProperties($properties = null)
    {
        if ($properties == null) {
            $properties = self::getProperties();
        }
        self::$properties = $properties;
    }

    /**
     * 查询一个bean
     *
     * @param string $name 名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return ApplicationContext::getBean($name);
    }

    /**
     * @return Application
     */
    public static function getApplication(){
        return ApplicationContext::getBean('application');
    }

    /**
     * 全局错误处理器
     *
     * @return ErrorHandler
     */
    public static function getErrorHandler()
    {
        return ApplicationContext::getBean('errorHanlder');
    }

    /**
     * 获取config bean
     *
     * @return mixed
     */
    public static function getProperties()
    {
        return ApplicationContext::getBean('config');
    }

    /**
     * 日志对象
     *
     * @return Logger
     */
    public static function getLogger()
    {
        return ApplicationContext::getBean('logger');
    }

    /**
     * 内部服务数据解包、打包
     *
     * @return IPack
     */
    public static function getPacker()
    {
        return ApplicationContext::getBean('packer');
    }

    /**
     * request对象
     *
     * @return web\Request
     */
    public static function getRequest()
    {
        return RequestContext::getRequest();
    }

    /**
     * response对象
     *
     * @return web\Response
     */
    public static function getResponse()
    {
        return RequestContext::getResponse();
    }

    /**
     * 获取定时器bean
     *
     * @return Timer
     */
    public static function getTimer()
    {
        return ApplicationContext::getBean('timer');
    }

    /**
     * trace级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function trace($message, array $context = array())
    {
        self::getLogger()->addTrace($message, $context);
    }

    /**
     * error级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function error($message, array $context = array())
    {
        self::getLogger()->error($message, $context);
    }

    /**
     * info级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function info($message, array $context = array())
    {
        self::getLogger()->info($message, $context);
    }

    /**
     * warning级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function warning($message, array $context = array())
    {
        self::getLogger()->warning($message, $context);
    }

    /**
     * debgu级别日志
     *
     * @param mixed $message 日志信息
     * @param array $context 附加信息
     */
    public static function debug($message, array $context = array())
    {
        self::getLogger()->debug($message, $context);
    }

    /**
     * 标记日志
     *
     * @param string $key 统计key
     * @param mixed  $val 统计值
     */
    public static function pushlog($key, $val)
    {
        self::getLogger()->pushLog($key, $val);
    }

    /**
     * 统计标记开始
     *
     * @param string $name 标记名
     */
    public static function profileStart(string $name)
    {
        self::getLogger()->profileStart($name);
    }

    /**
     * 统计标记结束
     *
     * @param string $name 标记名，必须和开始标记名称一致
     */
    public static function profileEnd($name)
    {
        self::getLogger()->profileEnd($name);
    }

    /**
     * 当前协程ID
     *
     * @return int
     */
    public static function getCoroutineId()
    {
        return \Swoole\Coroutine::getuid();
    }

    /**
     * @return bool 当前是否是worker状态
     */
    public static function isWorkerStatus()
    {
        if (self::$app == null) {
            return false;
        }
        $server = self::$app->getServer();
        if ($server != null && $server->taskworker == false) {
            return true;
        }
        return false;
    }

    /**
     * 命中率计算
     *
     * @param string $name  名称
     * @param int    $hit   命中
     * @param int    $total 总共
     */
    public static function counting(string $name, int $hit, $total = null)
    {
        self::getLogger()->counting($name, $hit, $total);
    }
}
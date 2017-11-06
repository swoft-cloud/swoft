<?php

namespace Swoft;

use Swoft\Base\ApplicationContext;
use Swoft\Base\Config;
use Swoft\Base\RequestContext;
use Swoft\Base\Timer;
use Swoft\Event\ApplicationEvent;
use Swoft\Log\Logger;
use Swoft\Pool\RedisPool;
use Swoft\Server\IServer;
use Swoft\Service\ConsulProvider;
use Swoft\Service\IPack;
use Swoft\Web\Application;

/**
 * 应用简写类
 *
 * @uses      App
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class App
{

    /**
     * 应用对象
     *
     * @var Application
     */
    public static $app;

    /**
     * 服务器对象
     *
     * @var IServer
     */
    public static $server;

    /**
     * config bean配置对象
     *
     * @var Config
     */
    public static $properties;

    /**
     * Swoft系统配置对象
     *
     * @var Config
     */
    public static $appProperties;

    /**
     * 是否初始化了crontab
     */
    public static $isInitCron = false;

    /**
     * 别名库
     *
     * @var array
     */
    private static $aliases = [
        '@Swoft' => __DIR__
    ];

    /**
     * 获取mysqlBean对象
     */
    public static function getMysqlPool()
    {
        return self::getBean('mysql');
    }

    /**
     * swoft版本
     *
     * @return string
     */
    public static function version()
    {
        return '0.';
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
     * 查询一个bean
     *
     * @param string $name 名称
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return ApplicationContext::getBean($name);
    }

    /**
     * @return Application
     */
    public static function getApplication()
    {
        return ApplicationContext::getBean('application');
    }

    /**
     * 获取config bean
     *
     * @return Config
     */
    public static function getProperties()
    {
        return ApplicationContext::getBean('config');
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
     * @return Config
     */
    public static function getAppProperties(): Config
    {
        return self::$appProperties;
    }

    /**
     * @param Config $appProperties
     */
    public static function setAppProperties(Config $appProperties)
    {
        self::$appProperties = $appProperties;
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
     * @return Web\Request
     */
    public static function getRequest()
    {
        return RequestContext::getRequest();
    }

    /**
     * response对象
     *
     * @return Web\Response
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
     * 发布事件
     *
     * @param string $name 发布的事件名称
     * @param ApplicationEvent|null $event 发布的时间对象
     * @param array $params 附加数据信息
     */
    public static function trigger(string $name, ApplicationEvent $event = null, ...$params)
    {
        ApplicationContext::publishEvent($name, $event, ...$params);
    }

    /**
     * 语言翻译
     *
     * @param string $category 翻译文件类别，比如xxx.xx/xx
     * @param array $params 参数
     * @param string $language 当前语言环境
     */
    public static function t(string $category, array $params, string $language = 'en')
    {
        return ApplicationContext::getBean('I18n')->translate($category, $params, $language);
    }

    /**
     * 注册别名
     *
     * @param string $alias 别名
     * @param string $path 路径
     */
    public static function setAlias(string $alias, string $path = null)
    {
        if (strncmp($alias, '@', 1)) {
            $alias = '@' . $alias;
        }

        // 删除别名
        if ($path == null) {
            unset(self::$aliases[$alias]);
            return;
        }

        // $path不是别名，直接设置
        $isAlias = strpos($path, '@');
        if ($isAlias === false) {
            self::$aliases[$alias] = $path;
            return;
        }

        // $path是一个别名
        if (isset(self::$aliases[$path])) {
            self::$aliases[$alias] = self::$aliases[$path];
            return;
        }

        list($root) = explode('/', $path);
        if (! isset(self::$aliases[$root])) {
            throw new \InvalidArgumentException("设置的根别名不存在，alias=" . $root);
        }

        $rootPath = self::$aliases[$root];
        $aliasPath = str_replace($root, "", $path);

        self::$aliases[$alias] = $rootPath . $aliasPath;
    }

    /**
     * 获取别名路径
     *
     * @param string $alias
     * @return string
     */
    public static function getAlias(string $alias)
    {
        if (isset(self::$aliases[$alias])) {
            return self::$aliases[$alias];
        }

        // $path不是别名，直接返回
        $isAlias = strpos($alias, '@');
        if ($isAlias === false) {
            return $alias;
        }

        list($root) = explode('/', $alias);
        if (! isset(self::$aliases[$root])) {
            throw new \InvalidArgumentException("设置的根别名不存在，alias=" . $root);
        }

        $rootPath = self::$aliases[$root];
        $aliasPath = str_replace($root, "", $alias);
        $path = $rootPath . $aliasPath;

        return $path;
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
     * @param mixed $val 统计值
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
     * @return bool 当前是否是worker状态
     */
    public static function isWorkerStatus()
    {
        if (self::$server == null) {
            return false;
        }
        $server = self::$server->getServer();

        if ($server != null && property_exists($server, 'taskworker') && $server->taskworker == false) {
            return true;
        }
        return false;
    }

    /**
     * 命中率计算
     *
     * @param string $name 名称
     * @param int $hit 命中
     * @param int $total 总共
     */
    public static function counting(string $name, int $hit, $total = null)
    {
        self::getLogger()->counting($name, $hit, $total);
    }
}

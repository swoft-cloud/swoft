<?php

namespace swoft\base;

use swoft\App;
use swoft\web\InnerService;
use swoft\web\Router;

/**
 * 应用基类
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Application
{
    /**
     * @var array
     */
    protected $server = [];

    /**
     * @var string 应用ID
     */
    protected $id;

    /**
     * @var string 应用名称
     */
    protected $name;

    /**
     * @var string 应用根目录
     */
    protected $basePath;

    /**
     * @var string 视图目录
     */
    protected $viewsPath;

    /**
     * @var string 运行日志目录
     */
    protected $runtimePath;

    /**
     * @var string http或tcp服务启动配置参数目录
     */
    protected $settingPath;

    /**
     * @var bool 是否使用第三方(consul/etcd/zk)注册服务
     */
    protected $useProvider = false;

    /**
     * @var string 控制器命令空间
     */
    protected $controllerNamespace = "app\\controllers";

    /**
     * @var string 内部服务命令空间
     */
    protected $serviceNameSpace = "app\\controllers\\services";

    public $count = 0;

    /**
     * 创建控制器
     *
     * @param string $path url路径
     * @param array  $info url参数
     *
     * @return array
     * <pre>
     *  [$controller, $action, $matches]
     * </pre>
     * @throws \InvalidArgumentException
     */
    public function createController(string $path, array $info)
    {
        $handler = $info['handler'];
        $matches = $info['matches'] ?? [];

        // Remove $matches[0] as [1] is the first parameter.
        if ($matches) {
            array_shift($matches);
            $matches = array_values($matches);
        }

        // is a \Closure or a callable object
        if (is_object($handler)) {
            return $matches ? $handler(...$matches) : $handler();
        }

        //// $handler is string

        // is array ['controller', 'action']
        if (is_array($handler)) {
            $segments = $handler;
        } elseif (is_string($handler)) {
            // e.g `controllers\Home@index` Or only `controllers\Home`
            $segments = explode('@', trim($handler));
        } else {
            App::error('Invalid route handler for URI: ' . $path);
            throw new \InvalidArgumentException('Invalid route handler for URI: ' . $path);
        }

        $action = '';
        $className = $segments[0];

        // Already assign action
        if (isset($segments[1])) {
            $action = $segments[1];

            // use dynamic action
        } elseif (isset($matches[0])) {
            $action = array_shift($matches);
        }

        $action = Router::convertNodeStr($action);
        $controller = ApplicationContext::getBean($className);

        return [$controller, $action, $matches];
    }

    /**
     * 调用内部服务
     *
     * @param array $data 调用参数信息
     *
     * @return array
     */
    public function runService(array $data)
    {
        $func = $data['func']?? '';
        $params = $data['params']?? [];

        list($servicePrefix, $method) = explode('::', $func);

        $namespace = $this->serviceNameSpace;
        $class = $servicePrefix . 'Service';
        $className = $namespace . "\\" . $class;
        if (!class_exists($className)) {
            App::error('内部服务调用的class不存在,class=' . $className);
            throw new \InvalidArgumentException('内部服务调用的class不存在,class=' . $className);
        }

        if ($className instanceof InnerService) {
            App::error('内部服务调用的class不是InnerService子类,class=' . $className);
            throw new \InvalidArgumentException('内部服务调用的class不是InnerService子类,class=' . $className);
        }

        if (empty($method)) {
            App::error('内部服务调用的class不是InnerService子类,class=' . $className);
            throw new \InvalidArgumentException('内部服务调用的method为空,method=' . $method);
        }

        /* @var $service InnerService */
        $service = App::getBean($className);
        $data = $service->run($method, $params);

        return $data;
    }

    /**
     * 注册全局错误解析
     */
    public function registerErrorHandler()
    {
        ini_set('display_errors', false);

        $errorHandler = App::getErrorHandler();
        $errorHandler->register();
    }

    /**
     * reload服务
     *
     * @param bool $reloadTask
     */
    public function reload($reloadTask = false)
    {
        $onlyTask = $reloadTask ? SIGUSR2 : SIGUSR1;
        posix_kill($this->server['managerPid'], $onlyTask);
    }

    /**
     * stop服务
     */
    public function stop()
    {
        $timeout = 5;
        $startTime = time();
        $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);

        $result = true;
        while (1) {
            $masterIslive = $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);
            if ($masterIslive) {
                if (time() - $startTime >= $timeout) {
                    $result = false;
                    break;
                }
                usleep(10000);
                continue;
            }

            break;
        }
        return $result;
    }

    /**
     * 服务是否已启动
     *
     * @return bool
     */
    public function isRunning()
    {
        $masterIsLive = false;
        $pFile = $this->server['pfile'];

        // pid 文件是否存在
        if (file_exists($pFile)) {
            // 文件内容解析
            $pidFile = file_get_contents($pFile);
            $pids = explode(',', $pidFile);

            $this->server['masterPid'] = $pids[0];
            $this->server['managerPid'] = $pids[1];
            $masterIsLive = $this->server['masterPid'] && @posix_kill($this->server['managerPid'], 0);
        }

        return $masterIsLive;
    }

    abstract public function start();
}

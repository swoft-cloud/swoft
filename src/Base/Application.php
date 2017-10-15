<?php

namespace Swoft\Base;

use Swoft\App;
use Swoft\Web\InnerService;
use Swoft\Web\Router;

/**
 * 应用基类
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Application
{

    /**
     * @var string 应用ID
     */
    protected $id;

    /**
     * @var string 应用名称
     */
    protected $name;

    /**
     * @var bool 是否使用第三方(consul/etcd/zk)注册服务
     */
    protected $useProvider = false;

    /**
     * @var string 内部服务命令空间
     */
    protected $serviceNameSpace = "App\\Controllers\\Services";

    public $count = 0;

    /**
     * 初始化
     */
    public function init()
    {
        App::$app = $this;

        // 注册全局错误错误
        $this->registerErrorHandler();
    }

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
            // e.g `Controllers\Home@index` Or only `Controllers\Home`
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

        /* @var Router $router */
        $router = App::getBean('router');
        list($className, $method) = $router->serviceMatch($func);

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
}

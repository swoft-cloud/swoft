<?php

namespace swoft\base;

use swoft\helpers\ArrayHelper;
use swoft\web\InnerService;
use swoft\web\Router;

/**
 *
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Application
{
    protected $id;
    protected $name;
    protected $beans;
    protected $params;
    protected $basePath;
    protected $viewsPath;
    protected $runtimePath;
    protected $settingPath;
    protected $defaultRoute = "index";
    protected $useProvider = false;
    protected $controllerNamespace = "app\\controllers";
    protected $serviceNameSpace = "app\\controllers\\services";

    /**
     * @var \swoole_lock
     */
    public $lock = null;

    public $count = 0;

    public function init()
    {
        // $this->lock = new \swoole_lock(SWOOLE_MUTEX);
    }

    public function run()
    {
        $this->parseCommand();
    }

    /**
     * @param string $path
     * @param array $info
     * @return array
     * [
     *
     * ]
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
        $controller =  ApplicationContext::getBean($className);

        return [$controller, $action, $matches];
    }

    /**
     * @param string $route
     *
     * @return array|bool
     */
    public function getPathRoute(string $route)
    {
        if ($route === '') {
            $route = $this->defaultRoute;
        }

        $route = trim($route, '/');
        if (strpos($route, '//') !== false) {
            return false;
        }

        if (strpos($route, '/') !== false) {
            list ($id, $route) = explode('/', $route, 2);
        } else {
            $id = $route;
            $route = '';
        }

        if (($pos = strrpos($route, '/')) !== false) {
            $id .= '/' . substr($route, 0, $pos);
            $route = substr($route, $pos + 1);
        }

        return [$id, $route];
    }

    public function runService($data)
    {
        $func = $data['func']?? "";
        $params = $data['params']?? [];

        list($servicePrefix, $method) = explode("::", $func);

        $namespace = $this->serviceNameSpace;
        $class = $servicePrefix."Service";
        $className = $namespace."\\".$class;
        if(!class_exists($className) || empty($method)){

        }

        /* @var $service InnerService*/
        $service = ApplicationContext::getBean($className);
        $data = $service->run($method, $params);

        return $data;
    }

    /**
     * @return bool
     */
    public function isUseProvider(): bool
    {
        return $this->useProvider;
    }

    abstract public function parseCommand();

    public function getLock()
    {
        if (!$this->lock) {
            $this->lock = new \swoole_lock(SWOOLE_MUTEX);
        }

        return $this->lock;
    }
}

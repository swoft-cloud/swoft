<?php

namespace swoft\base;

use swoft\helpers\ArrayHelper;
use swoft\rpc\RpcClient;

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
    protected $controllerNamespace = "app\\controllers";

    /**
     * @var \swoole_lock
     */
    public $lock = null;

    public $count = 0;

    public function init()
    {
        $this->lock = new \swoole_lock(SWOOLE_MUTEX);
        $this->loadCoreBeans();
    }

    public function run()
    {
        global $argv;
        $this->parseCommand($argv);
    }

    public function loadCoreBeans()
    {
        $beans = ArrayHelper::merge($this->coreBeans(), $this->beans);
        foreach ($beans as $beanName => $definition){
            ApplicationContext::createBean($beanName, $definition);
        }
    }

    public function createController(string $route)
    {
        if ($route === '') {
            $route = $this->defaultRoute;
        }

        // double slashes or leading/ending slashes may cause substr problem
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

        $controller = $this->getControllerById($id);
        if ($controller === null && $route !== '') {
            $controller = $this->getControllerById($id . '/' . $route);
            $route = '';
        }

        return $controller === null ? false : [$controller, $route];
    }

    public function getControllerById(string $id)
    {
        $pos = strrpos($id, '/');
        if ($pos === false) {
            $prefix = '';
            $className = $id;
        } else {
            $prefix = substr($id, 0, $pos + 1);
            $className = substr($id, $pos + 1);
        }


        // 匹配正则修改兼容controller LoginUser/testOne loginUser/testOne login-user/testOne
        if (!preg_match('%^[a-zA-Z][a-zA-Z0-9\\-_]*$%', $className)) {
            return null;
        }
        if ($prefix !== '' && !preg_match('%^[a-z0-9_/]+$%i', $prefix)) {
            return null;
        }
        // namespace和prefix保持一致，搜字母都大写或都小写，namespace app\controllers\SecurityKey; prefix=SecurityKey
        $className = str_replace(' ', '', ucwords(str_replace('-', ' ', $className))) . 'Controller';
        $className = ltrim($this->controllerNamespace . '\\' . str_replace('/', '\\', $prefix)  . $className, '\\');

        if (strpos($className, '-') !== false || !class_exists($className)) {
            return null;
        }

        if (is_subclass_of($className, 'swoft\base\Controller')) {
            return ApplicationContext::getBean($className);
        }else{
            return null;
        }
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

    public function coreBeans()
    {
        return [
            'urlManager' => ['class' => 'swoft\web\urlManager'],
            'filter' => ['class' => 'swoft\filter\FilterChain'],
        ];
    }

    abstract function parseCommand($argv);
}
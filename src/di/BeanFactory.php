<?php

namespace swoft\di;

use Monolog\Formatter\LineFormatter;
use swoft\App;
use swoft\base\Config;
use swoft\base\Timer;
use swoft\filter\FilterChain;
use swoft\filter\UriPattern;
use swoft\helpers\ArrayHelper;
use swoft\pool\balancer\RandomBalancer;
use swoft\pool\balancer\RoundRobinBalancer;
use swoft\service\JsonPacker;
use swoft\web\Application;
use swoft\web\Controller;
use swoft\web\ErrorHandler;
use swoft\web\Router;

/**
 *
 *
 * @uses      BeanFactory
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeanFactory implements BeanFactoryInterface
{

    /**
     * @var Container 容器
     */
    private static $container = null;


    public function __construct(array $definitions)
    {
        $definitions = self::merge($definitions);

        self::$container = new Container();
        self::$container->addDefinitions($definitions);
        self::$container->autoloadAnnotations();
        self::$container->initBeans();

        $requestMapping = self::$container->getRequestMapping();
        $this->registerRoutes($requestMapping);
        App::setProperties();
    }

    public static function getBean(string $name)
    {
        return self::$container->get($name);
    }

    public static function createBean(string $beanName, array $definition)
    {
        return self::$container->create($beanName, $definition);
    }

    public static function hasBean($name)
    {

    }

    /**
     * // 合并参数及初始化
     *
     * @param array $definitions
     *
     * @return array
     */
    private static function merge(array $definitions)
    {
        $definitions = ArrayHelper::merge(self::coreBeans(), $definitions);
        return $definitions;
    }

    private function registerRoutes(array $requestMapping)
    {
        /* @var Router $router */
        $router = self::getBean('router');
        foreach ($requestMapping as $className => $mapping) {
            if (!isset($mapping['prefix']) || !isset($mapping['routes'])) {
                continue;
            }

            $controllerPrefix = $mapping['prefix'];
            $controllerPrefix = $this->getControllerPrefix($controllerPrefix, $className);
            $routes = $mapping['routes'];
            /* @var Controller $controller */
            $controller = self::getBean($className);
            $actionPrefix = $controller->getActionPrefix();

            foreach ($routes as $route) {
                if (!isset($route['route']) || !isset($route['method']) || !isset($route['action'])) {
                    continue;
                }

                $mapRoute = $route['route'];
                $method = $route['method'];
                $action = $route['action'];

                $actionMethod = $this->getActionMethod($actionPrefix, $action);
                $mapRoute = empty($mapRoute) ? $actionMethod : $mapRoute;

                $uri = strpos($mapRoute, '/') === 0 ? $mapRoute : $controllerPrefix . "/" . $mapRoute;
                $handler = $className . "@" . $actionMethod;

                $router->map($method, $uri, $handler);
            }
        }

    }


    private function getActionMethod(string $actionPrefix, string $action)
    {
        $action = str_replace($actionPrefix, '', $action);
        $action = lcfirst($action);
        return $action;
    }

    private function getControllerPrefix(string $controllerPrefix, string $className)
    {
        if (!empty($controllerPrefix)) {
            return $controllerPrefix;
        }

        $reg = '/^.*\\\(\w+)Controller$/';
        $result = preg_match($reg, $className, $match);
        if ($result) {
            $prefix = "/" . lcfirst($match[1]);
            return $prefix;
        }
    }

    private static function coreBeans()
    {
        return [
            'config'             => ['class' => Config::class],
            'application'        => ['class' => Application::class],
            'errorHanlder'       => ['class' => ErrorHandler::class],
            "packer"             => ['class' => JsonPacker::class],
            'timer'              => ['class' => Timer::class],
            'randomBalancer'     => ['class' => RandomBalancer::class],
            'roundRobinBalancer' => ['class' => RoundRobinBalancer::class],
            'uriPattern'         => ['class' => UriPattern::class],
            'filter'             => [
                'class'            => FilterChain::class,
                'filterUriPattern' => '${uriPattern}'
            ],
            "lineFormate"        => [
                'class'      => LineFormatter::class,
                "format"     => '%datetime% [%level_name%] [%channel%] [logid:%logid%] [spanid:%spanid%] %message%',
                'dateFormat' => 'Y/m/d H:i:s'
            ],
        ];
    }
}
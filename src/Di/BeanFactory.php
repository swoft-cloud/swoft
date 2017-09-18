<?php

namespace Swoft\Di;

use Monolog\Formatter\LineFormatter;
use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Base\Config;
use Swoft\Event\ApplicationEvent;
use Swoft\Filter\FilterChain;
use Swoft\Helpers\ArrayHelper;
use Swoft\Pool\Balancer\RoundRobinBalancer;
use Swoft\Web\Application;
use Swoft\Web\Controller;
use Swoft\Web\ErrorHandler;
use Swoft\Web\Router;

/**
 * bean工厂
 *
 * @uses      BeanFactory
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeanFactory implements BeanFactoryInterface
{

    /**
     * @var Container 容器
     */
    private static $container = null;

    /**
     * BeanFactory constructor.
     *
     * @param array $definitions
     */
    public function __construct(array $definitions)
    {
        $definitions = self::merge($definitions);

        self::$container = new Container();
        self::$container->addDefinitions($definitions);
        self::$container->autoloadAnnotations();
        self::$container->initBeans();

        // 路由自动注册
        $requestMapping = self::$container->getRequestMapping();
        $this->registerRoutes($requestMapping);

        // 监听器注册
        $listeners = self::$container->getListeners();
        ApplicationContext::registerListeners($listeners);

        App::setProperties();
    }

    /**
     * 获取Bean
     *
     * @param string $name Bean名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return self::$container->get($name);
    }

    /**
     * 创建一个bean
     *
     * @param string $beanName
     * @param array  $definition
     *
     * @return mixed
     */
    public static function createBean(string $beanName, array $definition)
    {
        return self::$container->create($beanName, $definition);
    }

    /**
     * bean是否存在
     *
     * @param string $name bean名称
     *
     * @return bool
     */
    public static function hasBean(string $name)
    {
        return self::$container->hasBean($name);
    }

    private static function coreBeans()
    {
        return [
            'config'             => ['class' => Config::class],
            'application'        => ['class' => Application::class],
            'errorHandler'       => ['class' => ErrorHandler::class],
            'roundRobinBalancer' => ['class' => RoundRobinBalancer::class],
            'Filter'             => [
                'class'            => FilterChain::class,
                'filterUriPattern' => '${uriPattern}'
            ],
            "lineFormate"        => [
                'class'      => LineFormatter::class,
                "format"     => '%datetime% [%level_name%] [%channel%] [logid:%logid%] [spanid:%spanid%] %messages%',
                'dateFormat' => 'Y/m/d H:i:s'
            ],
        ];
    }

    /**
     * 自动注册路由
     *
     * @param array $requestMapping
     */
    private function registerRoutes(array $requestMapping)
    {
        foreach ($requestMapping as $className => $mapping) {
            if (!isset($mapping['prefix']) || !isset($mapping['routes'])) {
                continue;
            }

            // 控制器prefix
            $controllerPrefix = $mapping['prefix'];
            $controllerPrefix = $this->getControllerPrefix($controllerPrefix, $className);
            $routes = $mapping['routes'];

            /* @var Controller $controller */
            $controller = self::getBean($className);
            $actionPrefix = $controller->getActionPrefix();

            // 注册控制器对应的一组路由
            $this->registerRoute($className, $routes, $controllerPrefix, $actionPrefix);
        }
    }

    /**
     * 注册路由
     *
     * @param string $className        类名
     * @param array  $routes           控制器对应的路由组
     * @param string $controllerPrefix 控制器prefix
     * @param string $actionPrefix     action prefix
     */
    private function registerRoute(string $className, array $routes, string $controllerPrefix, string $actionPrefix)
    {
        /* @var Router $router */
        $router = self::getBean('router');

        // 循环注册路由
        foreach ($routes as $route) {
            if (!isset($route['route']) || !isset($route['method']) || !isset($route['action'])) {
                continue;
            }
            $mapRoute = $route['route'];
            $method = $route['method'];
            $action = $route['action'];

            // 解析注入action名称
            $actionMethod = $this->getActionMethod($actionPrefix, $action);
            $mapRoute = empty($mapRoute) ? $actionMethod : $mapRoute;

            // '/'开头的路由是一个单独的路由，未使用'/'需要和控制器组拼成一个路由
            $uri = strpos($mapRoute, '/') === 0 ? $mapRoute : $controllerPrefix . "/" . $mapRoute;
            $handler = $className . "@" . $actionMethod;

            // 注入路由规则
            $router->map($method, $uri, $handler);
        }
    }

    /**
     * 获取action方法
     *
     * @param string $actionPrefix 配置的默认action前缀
     * @param string $action       action方法
     *
     * @return string
     */
    private function getActionMethod(string $actionPrefix, string $action)
    {
        $action = str_replace($actionPrefix, '', $action);
        $action = lcfirst($action);
        return $action;
    }

    /**
     * 获取控制器prefix
     *
     * @param string $controllerPrefix 注解控制器prefix
     * @param string $className        控制器类名
     *
     * @return string
     */
    private function getControllerPrefix(string $controllerPrefix, string $className)
    {
        // 注解注入不为空，直接返回prefix
        if (!empty($controllerPrefix)) {
            return $controllerPrefix;
        }

        // 注解注入为空，解析控制器prefix
        $reg = '/^.*\\\(\w+)Controller$/';
        $result = preg_match($reg, $className, $match);
        if ($result) {
            $prefix = "/" . lcfirst($match[1]);
            return $prefix;
        }
    }

    /**
     * 合并参数及初始化
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
}

<?php

namespace Swoft\Di;

use Monolog\Formatter\LineFormatter;
use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Base\Config;
use Swoft\Event\Event;
use Swoft\Filter\FilterChain;
use Swoft\Helpers\ArrayHelper;
use Swoft\Pool\Balancer\RoundRobinBalancer;
use Swoft\Web\Application;
use Swoft\Web\ErrorHandler;

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

        // 监听器注册
        self::registerListeners();

        // 应用初始化加载事件
        $resourceDataProxy = self::$container->getResourceDataProxy();
        App::trigger(Event::APPLICATION_LOADER, null, $resourceDataProxy);
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

    /**
     *  注册监听器
     */
    private static function registerListeners()
    {
        // 监听器注册
        $listeners = self::$container->getResourceDataProxy()->listeners;
        ApplicationContext::registerListeners($listeners);
    }
}
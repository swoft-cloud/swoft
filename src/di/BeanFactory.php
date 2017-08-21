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
use swoft\web\ErrorHandler;

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
            'uriPattern'    => ['class' => UriPattern::class],
            'filter'             => [
                'class'             => FilterChain::class,
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
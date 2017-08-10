<?php

namespace swoft\di;

use DI\Container;
use DI\ContainerBuilder;
use Monolog\Formatter\LineFormatter;
use swoft\base\Config;
use swoft\base\Timer;
use swoft\filter\ExactUriPattern;
use swoft\filter\ExtUriPattern;
use swoft\filter\FilterChain;
use swoft\filter\PathUriPattern;
use swoft\helpers\ArrayHelper;
use swoft\App;
use swoft\pool\balancer\RandomBalancer;
use swoft\pool\balancer\RoundRobinBalancer;
use swoft\service\JsonPacker;
use swoft\web\Application;
use swoft\web\ErrorHandler;

/**
 * bean工厂
 *
 * @uses      BeanFactory
 * @version   2017年07月07日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeanFactory implements BeanFactoryInterface
{
    /**
     * bean引用正则匹配
     */
    const BEAN_REF_REG = '/^\$\{(.*)\}$/';

    /**
     * @var Container 容器
     */
    private static $container = null;

    /**
     * @var array bean配置数组
     */
    private static $beansConfig = [];

    /**
     * BeanFactory constructor.
     *
     * @param array $config 配置项
     */
    public function __construct(array $config)
    {
        // 合并参数及初始化
        self::$beansConfig = ArrayHelper::merge(self::coreBeans(), $config);

        // 初始化全局容器
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $container = $containerBuilder->build();
        self::$container = $container;

        // 初始化App配置
        App::setProperties();
    }

    /**
     * 查询Bean
     *
     * @param  string $name 名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        if (self::$container->has($name)) {
            return self::$container->get($name);
        }

        if (!isset(self::$beansConfig[$name])) {
            throw new \InvalidArgumentException("初始化Bean失败，bean不存在，beanName=" . $name);
        }

        $beanConfig = self::$beansConfig[$name];
        return self::createBean($name, $beanConfig);
    }

    /**
     * Bean是否存在容器中
     *
     * @param  string $name 名称
     *
     * @return bool
     */
    public static function hasBean($name)
    {
        return self::$container->has($name);
    }


    /**
     * 注入一个bean
     *
     * @param string       $beanName   名称
     * @param array|string $beanConfig 配置属性
     *
     * @return mixed
     */
    public static function createBean(string $beanName, array $beanConfig)
    {
        $className = $beanConfig;

        // 直接初始化一个类
        if (is_string($className)) {
            $object = \DI\object($className);
            self::$container->set($beanName, $object);
            return $object;
        }

        // 配置信息不完整,忽略
        if (!is_array($className) || !isset($beanConfig['class'])) {
            throw new \InvalidArgumentException("初始化Bean失败，配置信息不完整" . json_encode($beanConfig));
        }

        $constructorArgs = [];
        $className = $beanConfig['class'];
        unset($beanConfig['class']);

        // 构造函数初识别
        foreach ($beanConfig as $proName => $proVale) {
            if (is_array($proVale) && $proName === 0) {
                unset($proName);
                $constructorArgs = self::formateRefFields($proVale);
                continue;
            }
        }

        // 类属性识别
        $fields = self::formateRefFields($beanConfig);

        // 类初始化
        $object = \DI\object($className);
        $object = $object->constructor($constructorArgs);
        foreach ($fields as $name => $field) {
            $object->property($name, $field);
        }

        self::$container->set($beanName, $object);

        // 存在init方法初始化
        $bean = self::$container->get($beanName);
        if (method_exists($bean, 'init')) {
            $bean->init();
        }
        return $bean;
    }

    /**
     * 格式化bean引用属性
     *
     * @param array $fields
     *
     * @return array
     */
    private static function formateRefFields(array $fields)
    {
        $formateRef = [];
        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                $formateRef[$key] = self::formateRefFields($field);
                continue;
            }
            $refField = $field;
            $result = preg_match(self::BEAN_REF_REG, $field, $match);
            if (!$result) {
                $formateRef[$key] = $field;
                continue;
            }

            $refConfigProperties = explode(".", $match[1]);

            // 配置属性参数
            if (count($refConfigProperties) > 1) {
                $refField = self::getConfigPropertiesByRef($refConfigProperties);
                $formateRef[$key] = $refField;
                continue;
            }

            // bean引用存在
            if (self::$container->has($refConfigProperties[0])) {
                $refField = self::$container->get($refConfigProperties[0]);
                $formateRef[$key] = $refField;
                continue;
            }

            // bean引用不存在
            $refBeanName = $refConfigProperties[0];
            if (!isset(self::$beansConfig[$refBeanName])) {
                throw new \InvalidArgumentException("引用的bean不存在，beanName=" . $refBeanName);
            }

            $refBeanConfig = self::$beansConfig[$refBeanName];
            $refField = self::createBean($refBeanName, $refBeanConfig);
            $formateRef[$key] = $refField;
        }

        return $formateRef;
    }

    /**
     * 获取属性引用bean
     *
     * @param array $refConfigProperties
     *
     * @return mixed
     * @throws \Exception
     */
    private static function getConfigPropertiesByRef(array $refConfigProperties)
    {
        $configName = "config";
        if ($refConfigProperties[0] == $configName) {
            unset($refConfigProperties[0]);
        }

        $propertyVal = null;
        $config = self::$container->get($configName);

        // 属性解析
        foreach ($refConfigProperties as $refProName) {

            // config配置propterties识别
            if ($propertyVal == null && isset($config[$refProName])) {
                $propertyVal = $config[$refProName];
                continue;
            }

            // 不存在
            if (!isset($propertyVal[$refProName])) {
                throw new \InvalidArgumentException("$refConfigProperties is not exisit configed");
            }

            $propertyVal = $propertyVal[$refProName];
        }

        return $propertyVal;
    }

    /**
     * 常用beans
     *
     * @return array
     */
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
            'extUriPattern'      => ['class' => ExtUriPattern::class],
            'pathUriPattern'     => ['class' => PathUriPattern::class],
            'exactUriPattern'    => ['class' => ExactUriPattern::class],
            'filter'             => [
                'class'             => FilterChain::class,
                'filterUriPatterns' => [
                    '${exactUriPattern}',
                    '${extUriPattern}',
                    '${pathUriPattern}',
                ]
            ],
            "lineFormate"        => [
                'class'      => LineFormatter::class,
                "format"     => '%datetime% [%level_name%] [%channel%] [logid:%logid%] [spanid:%spanid%] %message%',
                'dateFormat' => 'Y/m/d H:i:s'
            ],
        ];
    }
}
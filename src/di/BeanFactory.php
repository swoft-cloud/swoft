<?php

namespace swoft\di;

use DI\Container;
use DI\ContainerBuilder;
use swoft\base\ApplicationContext;
use swoft\base\Config;
use swoft\base\Timer;
use swoft\exception\ErrorHandler;
use swoft\filter\FilterChain;
use swoft\helpers\ArrayHelper;
use swoft\App;
use swoft\pool\balancer\RandomBalancer;
use swoft\service\ConsulProvider;
use swoft\service\JsonPacker;
use swoft\web\Application;

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
    private $beansConfig = [];

    /**
     * BeanFactory constructor.
     *
     * @param array $config 配置项
     */
    public function __construct(array $config)
    {
        // 合并参数及初始化
        $this->beansConfig = ArrayHelper::merge($this->coreBeans(), $config);
        $this->init();

        // 初始化App配置
        App::setProperties();
    }


    /**
     * 初始化
     */
    private function init()
    {
        // 初始化全局容器
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $container = $containerBuilder->build();
        self::$container = $container;

        // 初始化应用上下文
        ApplicationContext::setContainer($container);

        foreach ($this->beansConfig as $beanName => $pros) {
            //已经注入
            if ($container->has($beanName)) {
                continue;
            }
            $this->createBean($beanName, $pros);
        }
    }

    /**
     * 注入一个bean
     *
     * @param string       $beanName   名称
     * @param array|string $beanConfig 配置属性
     *
     * @return bool
     */
    public function createBean(string $beanName, $beanConfig)
    {
        $className = $beanConfig;

        // 直接初始化一个类
        if (is_string($className)) {
            $object = \DI\object($className);
            self::$container->set($beanName, $object);
            return true;
        }

        // 配置信息不完整,忽略
        if (!is_array($className) || !isset($beanConfig['class'])) {
            return false;
        }

        $constructorArgs = [];
        $className = $beanConfig['class'];
        unset($beanConfig['class']);

        // 构造函数初识别
        foreach ($beanConfig as $proName => $proVale) {
            if (is_array($proVale) && $proName === 0) {
                unset($proName);
                $constructorArgs = $this->formateRefFields($proVale);
                continue;
            }
        }

        // 类属性识别
        $fields = $this->formateRefFields($beanConfig);

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

        return true;
    }

    /**
     * 返回容器
     *
     * @return Container
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * 查询Bean
     *
     * @param  string $name 名称
     *
     * @return mixed
     */
    public static function get(string $name)
    {
        return self::$container->get($name);
    }

    /**
     * 格式化bean引用属性
     *
     * @param array $fields
     *
     * @return array
     */
    private function formateRefFields(array $fields)
    {

        $formateRef = [];
        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                $formateRef[$key] = $this->formateRefFields($field);
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
                $refField = $this->getConfigPropertiesByRef($refConfigProperties);
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
            if (!isset($this->beansConfig[$refBeanName])) {
                throw new \InvalidArgumentException("引用的bean不存在，beanName=" . $refBeanName);
            }

            $refBeanConfig = $this->beansConfig[$refBeanName];
            $refField = $this->createBean($refBeanName, $refBeanConfig);
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
    private function getConfigPropertiesByRef(array $refConfigProperties)
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
    private function coreBeans()
    {
        return [
            'config'          => ['class' => Config::class],
            'application'     => ['class' => Application::class],
            'filter'          => ['class' => FilterChain::class],
            'errorHanlder'    => ['class' => ErrorHandler::class],
            "packer"          => ['class' => JsonPacker::class],
            'timer'           => ['class' => Timer::class],
            'serviceProvider' => ['class' => ConsulProvider::class],
            'randomBalancer'  => ['class' => RandomBalancer::class],
        ];
    }
}
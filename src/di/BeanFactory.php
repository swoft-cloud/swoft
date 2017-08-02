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
 *
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
     * @var Container
     */
    private static $container = null;

    private $beansConfig = [];

    public function __construct(array $config)
    {
        $this->beansConfig = ArrayHelper::merge($this->coreBeans(), $config);
        self::$container = $this->init();
        App::setProperties();
    }

    private function init()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $container = $containerBuilder->build();
        self::$container = $container;
        ApplicationContext::setContainer($container);

        foreach ($this->beansConfig as $beanName => $pros) {
            //已经注入
            if ($container->has($beanName)) {
                continue;
            }
            $this->createBean($beanName, $pros);
        }
    }

    private function createBean($beanName, $beanConfig)
    {
        $container = self::$container;
        $className = $beanConfig;
        if (is_string($className)) {
            $object = \DI\object($className);
            $container->set($beanName, $object);
            return true;
        }
        if (!is_array($className) || !isset($beanConfig['class'])) {
            return false;
        }
        $constructorArgs = [];
        $className = $beanConfig['class'];
        unset($beanConfig['class']);

        foreach ($beanConfig as $proName => $proVale) {
            if (is_array($proVale) && $proName === 0) {
                unset($proName);
                $constructorArgs = $this->formateRefFields($proVale);
                continue;
            }
        }

        $fields = $this->formateRefFields($beanConfig);

        $object = \DI\object($className);
        $object = $object->constructor($constructorArgs);
        foreach ($fields as $name => $field) {
            $object->property($name, $field);
        }

        $container->set($beanName, $object);

        // 存在init方法初始化
        $bean = $container->get($beanName);
        if (method_exists($bean, 'init')) {
            $bean->init();
        }

        return true;
    }

    /**
     * @return Container
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     *
     * @param $name
     *
     * @return mixed
     */
    public static function get($name)
    {
        return self::$container->get($name);
    }

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

    private function getConfigPropertiesByRef(array $refConfigProperties)
    {
        $configName = "config";
        if ($refConfigProperties[0] == $configName) {
            unset($refConfigProperties[0]);
        }

        $propertyVal = null;
        $config = self::$container->get($configName);

        foreach ($refConfigProperties as $refProName) {
            if ($propertyVal == null && isset($config[$refProName])) {
                $propertyVal = $config[$refProName];
                continue;
            }

            if (!isset($propertyVal[$refProName])) {
                throw new \Exception("$refConfigProperties is not exisit configed");
            }

            $propertyVal = $propertyVal[$refProName];
        }

        return $propertyVal;
    }
}
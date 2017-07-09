<?php

namespace swoft\di;

use DI\Container;
use DI\ContainerBuilder;
use swoft\base\ApplicationContext;
use swoft\helpers\ArrayHelper;
use swoft\Swf;

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
     * @var Container
     */
    private static $container = null;

    public function __construct(array $config)
    {
        $coreBeans = ArrayHelper::merge($this->coreBeans(), $config);
        self::$container = $this->init($coreBeans);
        Swf::setProperties();
    }

    private function init($coreBeans)
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $container = $containerBuilder->build();
        self::$container = $container;
        ApplicationContext::setContainer($container);

        foreach ($coreBeans as $beanName => $pros) {
            $className = $pros;
            if (is_string($className)) {
                $object = \DI\object($className);
                $container->set($beanName, $object);
                continue;
            }
            if (!is_array($className) || !isset($pros['class'])) {
                continue;
            }

            $constructorArgs = [];
            $className = $pros['class'];
            unset($pros['class']);

            foreach ($pros as $proName => $proVale) {
                if (is_array($proVale) && $proName === 0) {
                    unset($proName);
                    $constructorArgs = $this->formateFieldOrArgs($proVale);
                    continue;
                }
            }

            $fields = $this->formateFieldOrArgs($pros);

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
        }
    }

    /**
     * create beans by type
     *
     * below are some examples:
     *
     * // create with class name
     * BeanFactory::ApplicationContext('\swoft\web\UrlManage');
     *
     * // crreate with class configures
     * BeanFactory::ApplicationContext(
     *  [
     *      'class' => '\swoft\web\UrlManage',
     *      'field' => 'value1',
     *      'field2' => 'value'2
     *  ]
     * );
     *
     * @param string       $beanName    the name of bean
     * @param array|string $type        class definition
     * @param array        $params      constructor parameters
     * @return Object
     * @throws \Exception
     */
    public static function createBean($beanName, $type, $params = [])
    {
        if(is_string($type) && class_exists($type)){
            $object = \DI\object($type);
            $object->constructor($params);
            self::$container->set($beanName, $object);
            return self::$container->get($beanName);
        }

        if(!is_array($type) || !isset($type['class'])){
            throw new \Exception("error inject");
        }

        $className = $type['class'];
        unset($type['class']);

        $fields = $type;
        $object = \DI\object($className);
        $object = $object->constructor($params);
        foreach ($fields as $name => $value){
            $object = $object->property($name, $value);
        }

        self::$container->set($beanName, $object);

        // 存在init方法初始化
        $bean =self::$container->get($beanName);
        if (method_exists($bean, 'init')) {
            $bean->init();
        }

        return $bean;
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

    private function formateFieldOrArgs(array $fieldsOrArgs)
    {
        if (empty($fieldsOrArgs)) {
            return [];
        }

        $formateAry = [];
        $refReg = '\'/^\{(.*)\}$/\'';
        foreach ($fieldsOrArgs as $name => $value) {
            if(!is_string($value)){
                $formateAry[$name] = $value;
                continue;
            }
            $result = preg_match($refReg, $value, $match);
            if (!$result) {
                $formateAry[$name] = $value;
                continue;
            }

            $refBean = $match;
            if (!self::$container->has($refBean)) {
                throw new \Exception("bean is not inject ,name=" . $refBean);
            }
            $formateAry[$name] = self::$container->get($refBean);
        }
        return $formateAry;
    }


    private function coreBeans()
    {
        return [
            'application'           => ['class' => 'swoft\web\Application'],
            'urlManager'            => ['class' => 'swoft\web\urlManager'],
            'filter'                => ['class' => 'swoft\filter\FilterChain'],
            'errorHanlder'          => ['class' => 'swoft\exception\ErrorHandler'],
            'managerPool'           => ['class' => '\swoft\pool\ManagerPool'],
            'circuitBreakerManager' => ['class' => '\swoft\circuit\CircuitBreakerManager'],
            'logger'                => ['class' => '\swoft\log\Logger'],
            'config'                => ['class' => '\swoft\base\Config']
        ];
    }
}
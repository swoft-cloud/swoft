<?php

namespace swoft\base;

use DI\Container;
use swoft\web\Application;

/**
 * 应用上下文
 *
 * @uses      ApplicationContext
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ApplicationContext
{
    /**
     * @var Container 容器
     */
    private static $container;


    /**
     * Create beans by type
     *
     * Below are some examples:
     *
     * // create with class name
     * ApplicationContext::ApplicationContext('\swoft\web\UrlManage');
     *
     * // crreate with class configures
     * ApplicationContext::ApplicationContext(
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
     */
    public static function createBean($beanName, $type, $params = [])
    {
        $fields = [];
        $className = $type;
        if(is_array($type)){
            if(isset($type['class'])){
                $className = $type['class'];
            }else{
                $className = Application::class;
            }
            unset($type['class']);
            $fields = $type;
        }
        $object = \DI\object($className);
        $object = $object->constructor($params);
        foreach ($fields as $name => $value){
            $object = $object->property($name, $value);
        }
        $object = $object->method('init');
        self::$container->set($beanName, $object);

        return self::$container->get($beanName);
    }

    /**
     * 查询一个bean
     *
     * @param string $name bean名称
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return self::$container->get($name);
    }

    /**
     * bean是否存在
     *
     * @param string $name
     * @return bool
     */
    public static function containsBean($name)
    {
        return self::$container->has($name);
    }

    /**
     * @return Container 获取容器
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * @param Container $container 初始化容器
     */
    public static function setContainer($container)
    {
        self::$container = $container;
    }
}
<?php

namespace swoft\base;

use swoft\di\BeanFactory;
use swoft\event\ApplicationEvent;
use swoft\event\IApplicationListener;

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
     * 监听器集合
     *
     * @var array
     */
    private static $listeners = [];

    public static function registerListeners(array $listeners)
    {
        foreach ($listeners as $eventName => $eventListeners){
            foreach ($eventListeners as $listenerClassName){
                $listener = self::getBean($listenerClassName);
                self::addListeners($eventName, $listener);
            }
        }
    }

    public static function addListeners(string $name, IApplicationListener $listener)
    {
        self::$listeners[$name][] = $listener;
    }

    public static function publishEvent(string $name, ApplicationEvent $event = null)
    {
        if(!isset(self::$listeners[$name]) || !isset(self::$listeners[$name])){
            throw new \InvalidArgumentException("不存在事件监听器，name=".$name);
        }

        $listeners = self::$listeners[$name];

        /* @var IApplicationListener $listener */
        foreach ($listeners as $listener){
            $listener->onApplicationEvent($event);
            if($event instanceof ApplicationEvent && $event->isHandled()){
               break;
            }
        }
    }

    /**
     * 运行过程中创建一个Bean
     *
     * Below are some examples:
     *
     * // 类名称创建
     * ApplicationContext::createBean('name', '\swoft\web\UrlManage');
     *
     * // 配置信息创建，切支持properties.php配置引用和bean引用
     * ApplicationContext::createBean(
     *  [
     *      'class' => '\swoft\web\UrlManage',
     *      'field' => 'value1',
     *      'field2' => 'value'2
     *  ]
     * );
     *
     * @param string       $beanName the name of bean
     * @param array|string $type     class definition
     * @param array        $params   constructor parameters
     *
     * @return mixed
     */
    public static function createBean($beanName, $type, $params = [])
    {
        if (!empty($params) && is_array($type)) {
            array_unshift($type, $params);
        }
        
        return BeanFactory::createBean($beanName, $type);
    }

    /**
     * 查询一个bean
     *
     * @param string $name bean名称
     *
     * @return mixed
     */
    public static function getBean(string $name)
    {
        return BeanFactory::getBean($name);
    }

    /**
     * bean是否存在
     *
     * @param string $name Bean名称
     *
     * @return bool
     */
    public static function containsBean($name)
    {
        return BeanFactory::hasBean($name);
    }

}
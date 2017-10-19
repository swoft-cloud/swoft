<?php

namespace Swoft\Base;

use Swoft\Bean\BeanFactory;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\IApplicationListener;

/**
 * 应用上下文
 *
 * @uses      ApplicationContext
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ApplicationContext
{
    /**
     * 控制台
     */
    const CONSOLE = 0;

    /**
     * worker
     */
    const WORKER = 1;

    /**
     * task
     */
    const TASK = 2;

    /**
     * 自定义进程
     */
    const PROCESS = 3;

    /**
     * 默认worker
     *
     * @var int
     */
    private static $context = self::CONSOLE;

    /**
     * 监听器集合
     *
     * @var array
     */
    private static $listeners = [];

    /**
     * 运行过程中创建一个Bean
     *
     * Below are some examples:
     *
     * // 类名称创建
     * ApplicationContext::createBean('name', '\Swoft\Web\UrlManage');
     *
     * // 配置信息创建，切支持properties.php配置引用和bean引用
     * ApplicationContext::createBean(
     *  [
     *      'class' => '\Swoft\Web\UrlManage',
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

    /**
     * 获取当前运行环境
     *
     * @return int
     */
    public static function getContext(): int
    {
        return self::$context;
    }

    /**
     * 设置当前运行环境
     *
     * @param int $context
     */
    public static function setContext(int $context)
    {
        self::$context = $context;
    }

    /**
     * 初始化注册监听器
     *
     * @param array $listeners 监听器集合
     */
    public static function registerListeners(array $listeners)
    {
        foreach ($listeners as $eventName => $eventListeners) {
            foreach ($eventListeners as $listenerClassName) {
                $listener = self::getBean($listenerClassName);
                self::addListeners($eventName, $listener);
            }
        }
    }

    /**
     * 注册一个监听器
     *
     * @param string               $name     监听的事件名称
     * @param IApplicationListener $listener 监听器
     */
    public static function addListeners(string $name, IApplicationListener $listener)
    {
        self::$listeners[$name][] = $listener;
    }

    /**
     * 发布一个事件，一个事件可能会有多个监听器
     *
     * @param string                $name   发布的事件名称
     * @param ApplicationEvent|null $event  发布的时间对象
     * @param array                 $params 附加数据信息
     */
    public static function publishEvent(string $name, ApplicationEvent $event = null, ...$params)
    {
        if (!isset(self::$listeners[$name]) || !isset(self::$listeners[$name])) {
            throw new \InvalidArgumentException("不存在事件监听器，name=" . $name);
        }

        $listeners = self::$listeners[$name];

        // 循环触发多个监听器
        /* @var IApplicationListener $listener */
        foreach ($listeners as $listener) {
            $listener->onApplicationEvent($event, ...$params);
            // 是否需要执行后续的监听器
            if ($event instanceof ApplicationEvent && $event->isHandled()) {
                break;
            }
        }
    }
}

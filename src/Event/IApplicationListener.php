<?php

namespace Swoft\Event;

/**
 * 事件监听器接口
 *
 * @uses      IApplicationListener
 * @version   2017年08月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IApplicationListener
{
    /**
     * 事件执行逻辑
     *
     * @param ApplicationEvent|null $event     事件对象
     * @param array                 ...$params 参数
     */
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params);
}

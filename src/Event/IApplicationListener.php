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
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params);
}
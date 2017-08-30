<?php

namespace swoft\event;

/**
 * 所有事件名称
 *
 * @uses      Event
 * @version   2017年08月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Event
{
    // 请求前事件
    const BEFORE_REQUEST = "beforeRequest";

    // 请求后事件
    const AFTER_REQUEST = "afterRequest";

    // rpc前事件
    const BEFORE_RECEIVE = "beforeReceive";
}
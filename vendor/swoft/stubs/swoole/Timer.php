<?php
/**
 * swoole-ide-helper.
 *
 * Author: wudi <wudi23@baidu.com>
 * Date: 2016/02/17
 */

namespace Swoole;

/**
 * Class Timer
 *
 * 异步定时器
 *
 * @package Swoole
 */
class Timer
{
    /**
     * 设置一个间隔时钟定时器，与after定时器不同的是tick定时器会持续触发，直到调用swoole_timer_clear清除。与swoole_timer_add不同的是tick定时器可以存在多个相同间隔时间的定时器。
     *
     * @param int $ms 指定时间，单位为毫秒
     * @param callable $callback 时间到期后所执行的函数，必须是可以调用的。callback函数不接受任何参数
     * @param mixed $param 回调参数
     */
    static function tick($ms, callable $callback, $param = null)
    {

    }

    /**
     * 在指定的时间后执行函数，需要swoole-1.7.7以上版本
     *
     * @param int $ms 指定时间，单位为毫秒
     * @param callable $callback 时间到期后所执行的函数，必须是可以调用的。callback函数不接受任何参数
     */
    static function after($ms, callable $callback)
    {

    }

    /**
     * 使用定时器ID来删除定时器
     *
     * @param int $timerId 定时器ID，调用swoole_timer_add/swoole_timer_after 后会返回一个整数的ID
     */
    static function clear($timerId)
    {

    }
}
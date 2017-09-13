<?php

namespace Swoft\Base;

use Swoft\App;
use Swoft\Di\Annotation\Bean;

/**
 * 定时器
 *
 * @Bean("timer")
 * @uses      Timer
 * @version   2017年07月17日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Timer
{
    /**
     * 日志统计前缀
     */
    const TIMER_PREFIX = "timer.";

    /**
     * @var array 所有定时器
     */
    private $timers = [];

    /**
     * 添加一个定时器，只执行一次
     *
     * @param string   $name     名称
     * @param int      $time     毫秒
     * @param callable $callback 回调函数
     * @param  array   $params   参数
     *
     * @return int
     */
    public function addAfterTimer(string $name, int $time, callable $callback, $params = [])
    {
        array_unshift($params, $name, $callback);
        $tid = swoole_timer_after($time, [$this, 'timerCallback'], $params);
        $this->timers[$name][$tid] = $tid;
        return $tid;
    }

    /**
     * 添加一个定时器，每隔时间执行
     *
     * @param string   $name     名称
     * @param int      $time     毫秒
     * @param callable $callback 回调函数
     * @param    array $params   参数
     *
     * @return int
     */
    public function addTickTimer(string $name, int $time, $callback, $params = [])
    {
        array_unshift($params, $name, $callback);

        $tid = swoole_timer_tick($time, [$this, 'timerCallback'], $params);

        $this->timers[$name][$tid] = $tid;

        return $tid;
    }

    /**
     * 移除一个定时器
     *
     * @param string $name 定时器名称
     *
     * @return bool
     */
    public function clearTimerByName(string $name)
    {
        if (!isset($this->timers[$name])) {
            return true;
        }
        foreach ($this->timers[$name] as $tid => $tidVal) {
            swoole_timer_clear($tid);
        }
        unset($this->timers[$name]);

        return true;
    }

    /**
     * 定时器回调函数
     *
     * @param array $params 参数传递
     */
    public function timerCallback($params)
    {
        if (count($params) < 2) {
            return;
        }
        $name = array_shift($params);
        $callback = array_shift($params);

        $this->beforeTimer($name);

        $callbackParams = array_values($params);

        if (is_array($callback)) {
            list($class, $method) = $callback;
            $class->$method(...$callbackParams);
        } else {
            $callback(...$callbackParams);
        }

        $this->afterTimer($name);
    }

    /**
     * 定时器初始化
     *
     * @param string $name 名称
     */
    private function beforeTimer(string $name)
    {
        $contextData = [
            'logid'       => uniqid(),
            'spanid'      => 0,
            'uri'         => $this->getTimerUri($name),
            'requestTime' => microtime(true)
        ];
        RequestContext::setContextData($contextData);
    }

    /**
     * 定时器后续处理
     *
     * @param string $name 名称
     */
    private function afterTimer(string $name)
    {
        unset($this->timers[$name]);
        // 目前有bug,协程发生切换，先忽略

        //App::getLogger()->appendNoticeLog();
        //RequestContext::destory();
    }

    /**
     * 查询定时器日志KEY
     *
     * @param string $name 名称
     *
     * @return string
     */
    private function getTimerUri(string $name)
    {
        return self::TIMER_PREFIX . $name;
    }
}

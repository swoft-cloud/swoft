<?php

namespace swoft\base;

use swoft\App;

/**
 *
 *
 * @uses      Timer
 * @version   2017年07月17日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Timer
{
    const TIMER_PREFIX = "timer.";

    private $timers = [];

    /**
     * @param string   $name
     * @param int      $time        毫秒
     * @param callable $callback
     * @param          $params
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

    public function addTickTimer(string $name, int $time, $callback, $params = [])
    {
        array_unshift($params, $name, $callback);

        $tid = swoole_timer_tick($time, [$this, 'timerCallback'], $params);

        $this->timers[$name][$tid] = $tid;

        return $tid;
    }

    public function clearTimerByName(string $name)
    {
        if(!isset($this->timers[$name])){
            return true;
        }
        foreach ($this->timers[$name] as $tid => $tidVal){
            swoole_timer_clear($tid);
        }
        unset($this->timers[$name]);

        return true;
    }

    public function timerCallback($params)
    {
        if(count($params) < 2){
            return ;
        }
        $name = array_shift($params);
        $callback = array_shift($params);

        $this->beforeTimer($name);

        $callbackParams = array_values($params);

        if(is_array($callback)){
            list($class, $method) = $callback;
            $class->$method(...$callbackParams);
        }else{
            $callback(...$callbackParams);
        }

        $this->afterTimer($name);
    }

    private function beforeTimer(string $name)
    {
        $contextData = [
            'logid' => uniqid(),
            'spanid' => 0,
            'uri' => $this->getTimerUri($name),
            'requestTime' => microtime(true)
        ];
        RequestContext::setContextData($contextData);
    }

    private function afterTimer(string $name)
    {
        unset($this->timers[$name]);
        App::getLogger()->appendNoticeLog();
        RequestContext::destory();
    }

    private function getTimerUri($name)
    {
        return self::TIMER_PREFIX.$name;
    }
}
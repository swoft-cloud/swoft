<?php

namespace swoft\circuit;

/**
 * 开启状态及切换(open)
 *
 * 1. 重置failCounter=0 successCounter=0
 * 2. 请求立即返回错误响应
 * 3. 定时器一定时间后切换为半开状态(open)
 *
 * @uses      OpenState
 * @version   2017年07月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class OpenState extends CircuitBreakerState
{
    function doCall($callback, $params = [], $fallback = null)
    {
        $data = $this->circuitBreaker->fallback();

        // 开启定时器
        $nowTime = time();

        if ($this->circuitBreaker->isOpen()
            && $nowTime > $this->circuitBreaker->getSwithOpenToHalfOpenTime()
        ) {
            $delayTime = $this->circuitBreaker->getDelaySwithTimer();
            swoole_timer_after($delayTime, [$this, 'delayCallback']);
        }

        return $data;
    }

    public function delayCallback(){
        if($this->circuitBreaker->isOpen()){
            $this->circuitBreaker->swithToHalfState();
        }
    }
}
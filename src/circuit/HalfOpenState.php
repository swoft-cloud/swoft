<?php

namespace swoft\circuit;

/**
 * 半开状态及切换(half-open)
 *
 * 1. 重置failCounter=0
 * 2. 重置successCounter=0
 * 3. 操作成功successCounter计数
 * 4. 操作失败failCounter计数
 * 5. 连续操作成功一定计数，切换为close状态
 * 6. 连续操作失败一定计数，切换为open
 * 7. 同一并发时间只有一个请求执行
 *
 * @uses      HalfOpenState
 * @version   2017年07月02日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class HalfOpenState extends CircuitBreakerState
{
    function doCall($callback, $params = [], $fallback = null)
    {
        // 加锁
        $lock = $this->circuitBreaker->getHalfOpenLock();
        $lock->lock();
        $data = false;
        try {
            $data = \Swoole\Coroutine::call_user_func_array($callback, $params);
            $this->circuitBreaker->incSuccessCount();
        } catch (\Exception $e) {
            $this->circuitBreaker->incFailCount();
            $data = $this->circuitBreaker->$fallback($fallback);
        }

        $failCount = $this->circuitBreaker->getFailCounter();
        $successCount = $this->circuitBreaker->getSuccessCounter();
        $swithToFailCount = $this->circuitBreaker->getSwithToFailCount();
        $swithToSuccessCount = $this->circuitBreaker->getSwithToSuccessCount();

        if($failCount >= $swithToFailCount && $this->circuitBreaker->isHalfOpen()){
            $this->circuitBreaker->swithToOpenState();
        }

        if($successCount >= $swithToSuccessCount){
            $this->circuitBreaker->swithToCloseState();
        }

        // 释放锁
        $lock->unlock();

        return $data;
    }
}
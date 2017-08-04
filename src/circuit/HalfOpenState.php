<?php

namespace swoft\circuit;

use swoft\App;

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
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HalfOpenState extends CircuitBreakerState
{
    /**
     * 熔断器调用
     *
     * @param mixed $callback 回调函数
     * @param array $params   参数
     * @param mixed $fallback 失败回调
     *
     * @return mixed 返回结果
     */
    function doCall($callback, $params = [], $fallback = null)
    {
        // 加锁
        $lock = $this->circuitBreaker->getHalfOpenLock();
        $lock->lock();
        list($class ,$method) = $callback;

        try {
            if($class === null){
                throw new \Exception($this->getServiceName()."服务, 建立连接失败(null)");
            }

            if($class instanceof  \Swoole\Coroutine\Client && $class->isConnected() == false){
                throw new \Exception($this->circuitBreaker->serviceName."服务,当前连接已断开");
            }

            $data = $class->$method(...$params);
            $this->circuitBreaker->incSuccessCount();
            App::trace($this->getServiceName()."服务，当前[半开状态]，尝试执行成");
        } catch (\Exception $e) {
            $this->circuitBreaker->incFailCount();
            $data = $this->circuitBreaker->fallback($fallback);
            App::error($this->getServiceName()."服务，当前[半开状态]，尝试执行失败, error=".$e->getMessage());
        }

        $failCount = $this->circuitBreaker->getFailCounter();
        $successCount = $this->circuitBreaker->getSuccessCounter();
        $swithToFailCount = $this->circuitBreaker->getSwithToFailCount();
        $swithToSuccessCount = $this->circuitBreaker->getSwithToSuccessCount();

        if($failCount >= $swithToFailCount && $this->circuitBreaker->isHalfOpen()){
            $this->circuitBreaker->swithToOpenState();
            App::trace($this->getServiceName()."服务，当前[半开状态]，失败次数达到上限，开始切换到开启状态");
        }

        if($successCount >= $swithToSuccessCount){
            $this->circuitBreaker->swithToCloseState();
            App::trace($this->getServiceName()."服务，当前[半开状态]，成功次数达到上限，服务以及恢复，开始切换到关闭状态");

        }

        // 释放锁
        $lock->unlock();

        App::trace($this->getServiceName()."服务，当前[半开状态], failCount=".$failCount." successCount=".$successCount);

        return $data;
    }
}
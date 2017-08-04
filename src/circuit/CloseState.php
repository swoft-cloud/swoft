<?php

namespace swoft\circuit;

use swoft\App;

/**
 * 关闭状态及切换(close)
 *
 * 1. 重置failCounter=0 successCount=0
 * 2. 操作失败，failCounter计数
 * 3. 操作失败一定计数，切换为open开启状态
 *
 * @uses      CloseState
 * @version   2017年07月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CloseState extends CircuitBreakerState
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
        list($class ,$method) = $callback;

        try {
            if($class == null){
                throw new \Exception($this->circuitBreaker->serviceName."服务,连接建立失败(null)");
            }

            if($class instanceof  \Swoole\Coroutine\Client && $class->isConnected() == false){
                throw new \Exception($this->circuitBreaker->serviceName."服务,当前连接已断开");
            }
            $data = $class->$method(...$params);

        } catch (\Exception $e) {
            if($this->circuitBreaker->isClose()){
                $this->circuitBreaker->incFailCount();
            }

            App::error($this->circuitBreaker->serviceName."服务，当前[关闭状态]，服务端调用失败，开始服务降级容错处理，error=".$e->getMessage());
            $data = $this->circuitBreaker->fallback($fallback);
        }

        $failCount = $this->circuitBreaker->getFailCounter();
        $swithToFailCount = $this->circuitBreaker->getSwithToFailCount();
        if($failCount >= $swithToFailCount && $this->circuitBreaker->isClose()){
            App::trace($this->circuitBreaker->serviceName."服务，当前[关闭状态]，服务失败次数达到上限，开始切换为开启状态，failCount=".$failCount);
            $this->circuitBreaker->swithToOpenState();
        }

        App::trace($this->circuitBreaker->serviceName."服务，当前[关闭状态]，failCount=".$this->circuitBreaker->getFailCounter());
        return $data;
    }
}
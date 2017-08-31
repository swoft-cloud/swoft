<?php

namespace Swoft\Circuit;

use Swoft\App;

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
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class OpenState extends CircuitBreakerState
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
        $data = $this->circuitBreaker->fallback();

        App::trace($this->getServiceName()."服务，当前[开启状态]，执行服务fallback服务降级容错处理");
        // 开启定时器
        $nowTime = time();

        if ($this->circuitBreaker->isOpen()
            && $nowTime > $this->circuitBreaker->getSwithOpenToHalfOpenTime()
        ) {
            $delayTime = $this->circuitBreaker->getDelaySwithTimer();

            // 定时器不是严格的，新增3s,容错时间
            $swithToHalfStateTime = $nowTime + ($delayTime / 1000) + 3;
            App::getTimer()->addAfterTimer('openState', $delayTime, [$this, 'delayCallback']);
            $this->circuitBreaker->setSwithOpenToHalfOpenTime($swithToHalfStateTime);

            App::trace($this->getServiceName()."服务，当前[开启状态]，创建延迟触发器，一段时间后状态切换为半开状态");
        }

        return $data;
    }

    /**
     * 定时器延迟执行
     */
    public function delayCallback(){
        if($this->circuitBreaker->isOpen()){
            App::debug($this->getServiceName()."服务,当前服务[开启状态]，延迟触发器已触发，准备开始切换到半开状态");
            $this->circuitBreaker->swithToHalfState();
        }
    }
}
<?php

namespace swoft\circuit;

use swoft\App;

/**
 *
 *
 * @uses      CircuitBreaker
 * @version   2017年07月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CircuitBreaker
{
    const CLOSE = "close";

    const OPEN = "open";

    const HALF_OPEN_STATE = "halfOpenState";

    const UNINIT = "uninit";

    /**
     * @var int 错误请求计数
     */
    public $failCounter = 0;

    /**
     * @var int 成功请求计数
     */
    public $successCounter = 0;


    /**
     * @var int 开启状态切换到半开状态时间
     */
    public $swithOpenToHalfOpenTime = 0;

    /**
     * @var string 服务名称
     */
    public $serviceName = "breakerService";

    /**
     * @var CircuitBreakerState 熔断器状态，开启、半开、关闭
     */
    private $circuitState = null;

    /**
     * @var \swoole_lock 半开状态锁
     */
    private $halfOpenLock = null;

    /**
     * @var int 连续失败次数，如果到达，状态切换为open
     */
    private $swithToFailCount = 6;

    /**
     * @var int 连续成功次数，如果到达，状态切换为close
     */
    private $swithToSuccessCount = 6;

    /**
     * @var int 单位毫秒
     */
    private $delaySwithTimer = 5000;

    public function init()
    {
        // 状态初始化
        $this->circuitState = new CloseState($this);
        $this->halfOpenLock = new \swoole_lock(SWOOLE_MUTEX);
    }

    public function call($callback, $params = [], $fallback = null)
    {
        return $this->circuitState->doCall($callback, $params, $fallback);
    }

    public function incFailCount()
    {
        $this->failCounter++;
    }

    public function incSuccessCount()
    {
        $this->successCounter++;
    }

    public function isClose()
    {
        return $this->circuitState instanceof CloseState;
    }

    public function isOpen()
    {
        return $this->circuitState instanceof OpenState;
    }

    public function isHalfOpen()
    {
        return $this->circuitState instanceof HalfOpenState;
    }

    public function swithToCloseState()
    {
        App::debug($this->serviceName."服务，当前[".$this->getCurrrentState()."]，熔断器状态切换，切换到[关闭]状态");
        $this->circuitState = new CloseState($this);
    }

    public function swithToOpenState()
    {
        App::debug($this->serviceName."服务，当前[".$this->getCurrrentState()."]，熔断器状态切换，切换到[开启]状态");
        $this->circuitState = new OpenState($this);
    }

    public function swithToHalfState()
    {
        App::debug($this->serviceName."服务，当前[".$this->getCurrrentState()."]，熔断器状态切换，切换到[半开]状态");

        $this->circuitState = new HalfOpenState($this);
    }

    public function fallback($fallback = null)
    {
        if ($fallback == null) {
            App::debug($this->serviceName."服务，当前[".$this->getCurrrentState()."]，服务降级处理，fallback未定义");
            return null;
        }

        if(is_array($fallback) && count($fallback) == 2){
            list($className, $method) = $fallback;
            App::debug($this->serviceName."服务，服务降级处理，执行fallback, class=".$className." method=".$method);
            return $className->$method();
        }

        return null;
    }

    public function getCurrrentState()
    {
        if ($this->circuitState instanceof CloseState) {
            return self::CLOSE;
        }
        if ($this->circuitState instanceof HalfOpenState) {
            return self::HALF_OPEN_STATE;
        }

        if ($this->circuitState instanceof OpenState) {
            return self::OPEN;
        }
        return self::UNINIT;
    }

    public function initCounter()
    {
        $this->failCounter = 0;
        $this->successCounter = 0;
    }

    /**
     * @return int
     */
    public function getFailCounter(): int
    {
        return $this->failCounter;
    }

    /**
     * @return int
     */
    public function getSuccessCounter(): int
    {
        return $this->successCounter;
    }

    /**
     * @return int
     */
    public function getSwithToFailCount(): int
    {
        return $this->swithToFailCount;
    }

    /**
     * @return int
     */
    public function getSwithToSuccessCount(): int
    {
        return $this->swithToSuccessCount;
    }

    /**
     * @return int
     */
    public function getSwithOpenToHalfOpenTime(): int
    {
        return $this->swithOpenToHalfOpenTime;
    }

    /**
     * @param int $swithOpenToHalfOpenTime
     */
    public function setSwithOpenToHalfOpenTime(int $swithOpenToHalfOpenTime)
    {
        $this->swithOpenToHalfOpenTime = $swithOpenToHalfOpenTime;
    }

    /**
     * @return int
     */
    public function getDelaySwithTimer(): int
    {
        return $this->delaySwithTimer;
    }

    /**
     * @return \swoole_lock
     */
    public function getHalfOpenLock()
    {
        return $this->halfOpenLock;
    }
}

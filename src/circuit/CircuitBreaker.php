<?php

namespace swoft\circuit;

use swoft\base\ApplicationContext;
use swoft\rpc\RpcClient;

/**
 *
 *
 * @uses      CircuitBreaker
 * @version   2017年07月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CircuitBreaker  extends AbstractCircuitBreaker
{
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
     * @var CircuitBreakerState 熔断器状态，开启、半开、关闭
     */
    private $circuitState = null;

    /**
     * @var \swoole_lock 半开状态锁
     */
    private $halfOpenLock = null;

    public function __construct(CircuitBreakerManager $cbm)
    {
        // 配置初始化
        $this->swithToSuccessCount = $cbm->swithToSuccessCount;
        $this->swithToFailCount = $cbm->swithToFailCount;
        $this->delaySwithTimer = $cbm->delaySwithTimer;

        // 状态初始化
        $this->swithToCloseState();
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
        $this->circuitState = new CloseState($this);
    }

    public function swithToOpenState()
    {
        $this->circuitState = new OpenState($this);
    }

    public function swithToHalfState()
    {
        $this->circuitState = new HalfOpenState($this);
    }

    public function fallback($fallback = null)
    {
        if ($fallback == null) {
            return false;
        }
        return \Swoole\Coroutine::call_user_func($fallback);
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

<?php

namespace Swoft\Circuit;

use Swoft\App;

/**
 * 熔断器
 *
 * @uses      CircuitBreaker
 * @version   2017年07月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CircuitBreaker
{
    /**
     * 关闭状态
     */
    const CLOSE = "close";

    /**
     * 开启状态
     */
    const OPEN = "open";

    /**
     * 半开起状态
     */
    const HALF_OPEN_STATE = "halfOpenState";

    /**
     * 未初始化
     */
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

    /**
     * 初始化
     */
    public function init()
    {
        // 状态初始化
        $this->circuitState = new CloseState($this);
        $this->halfOpenLock = new \swoole_lock(SWOOLE_MUTEX);
    }

    /**
     * 熔断器调用
     *
     * @param mixed $callback 回调函数
     * @param array $params   参数
     * @param mixed $fallback 失败回调
     *
     * @return mixed 返回结果
     */
    public function call($callback, $params = [], $fallback = null)
    {
        return $this->circuitState->doCall($callback, $params, $fallback);
    }

    /**
     * 失败计数
     */
    public function incFailCount()
    {
        $this->failCounter++;
    }

    /**
     * 成功计数
     */
    public function incSuccessCount()
    {
        $this->successCounter++;
    }

    /**
     * 是否是关闭状态
     *
     * @return bool
     */
    public function isClose()
    {
        return $this->circuitState instanceof CloseState;
    }

    /**
     * 是否是开启状态
     *
     * @return bool
     */
    public function isOpen()
    {
        return $this->circuitState instanceof OpenState;
    }

    /**
     * 是否是半开状态
     *
     * @return bool
     */
    public function isHalfOpen()
    {
        return $this->circuitState instanceof HalfOpenState;
    }

    /**
     * 切换到关闭
     */
    public function swithToCloseState()
    {
        App::debug($this->serviceName . "服务，当前[" . $this->getCurrrentState() . "]，熔断器状态切换，切换到[关闭]状态");
        $this->circuitState = new CloseState($this);
    }

    /**
     * 切换到开启
     */
    public function swithToOpenState()
    {
        App::debug($this->serviceName . "服务，当前[" . $this->getCurrrentState() . "]，熔断器状态切换，切换到[开启]状态");
        $this->circuitState = new OpenState($this);
    }

    /**
     * 切换到半开
     */
    public function swithToHalfState()
    {
        App::debug($this->serviceName . "服务，当前[" . $this->getCurrrentState() . "]，熔断器状态切换，切换到[半开]状态");

        $this->circuitState = new HalfOpenState($this);
    }

    /**
     * 降级处理
     *
     * @param mixed $fallback
     *
     * @return null
     */
    public function fallback($fallback = null)
    {
        if ($fallback == null) {
            App::debug($this->serviceName . "服务，当前[" . $this->getCurrrentState() . "]，服务降级处理，fallback未定义");
            return null;
        }

        if (is_array($fallback) && count($fallback) == 2) {
            list($className, $method) = $fallback;
            App::debug($this->serviceName . "服务，服务降级处理，执行fallback, class=" . $className . " method=" . $method);
            return $className->$method();
        }

        return null;
    }

    /**
     * 当前状态
     *
     * @return string
     */
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

    /**
     * 初始化计数
     */
    public function initCounter()
    {
        $this->failCounter = 0;
        $this->successCounter = 0;
    }

    /**
     * 获取失败计数
     *
     * @return int
     */
    public function getFailCounter(): int
    {
        return $this->failCounter;
    }

    /**
     * 获取成功计数
     *
     * @return int
     */
    public function getSuccessCounter(): int
    {
        return $this->successCounter;
    }

    /**
     * 获取开始切换到失败的计数
     *
     * @return int
     */
    public function getSwithToFailCount(): int
    {
        return $this->swithToFailCount;
    }

    /**
     * 开始切换到成功的计数
     *
     * @return int
     */
    public function getSwithToSuccessCount(): int
    {
        return $this->swithToSuccessCount;
    }

    /**
     * 获取开启切换到半开的时间
     *
     * @return int
     */
    public function getSwithOpenToHalfOpenTime(): int
    {
        return $this->swithOpenToHalfOpenTime;
    }

    /**
     * 初始化开启切换到半开的时间
     *
     * @param int $swithOpenToHalfOpenTime
     */
    public function setSwithOpenToHalfOpenTime(int $swithOpenToHalfOpenTime)
    {
        $this->swithOpenToHalfOpenTime = $swithOpenToHalfOpenTime;
    }

    /**
     * 关闭切换到开启延迟定时器时间
     *
     * @return int
     */
    public function getDelaySwithTimer(): int
    {
        return $this->delaySwithTimer;
    }

    /**
     * 半开状态锁
     *
     * @return \swoole_lock
     */
    public function getHalfOpenLock()
    {
        return $this->halfOpenLock;
    }
}

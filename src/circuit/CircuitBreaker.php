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
class CircuitBreaker
{
    /**
     * @var CircuitBreakerState 熔断器状态，开启、半开、关闭
     */
    private $circuitState = null;

    /**
     * @var int 错误请求计数
     */
    private $failCounter = 0;

    /**
     * @var int 成功请求计数
     */
    private $successCounter = 0;

    /**
     * @var int 连续失败次数，如果到达，状态切换为open
     */
    private $swithToFailCount = 10;

    /**
     * @var int 连续成功次数，如果到达，状态切换为close
     */
    private $swithToSuccessCount = 10;

    /**
     * @var int 开启状态切换到半开状态时间
     */
    private $swithOpenToHalfOpenTime = 0;

    /**
     * @var int 单位毫秒
     */
    private $delaySwithTimer = 5000;

    private $halfOpenLock = null;

    public function __construct()
    {
        $this->swithToCloseState();
        $this->halfOpenLock = new \swoole_lock(SWOOLE_MUTEX);
    }

    public function call($callback, $params= [], $fallback = null){
        return $this->circuitState->doCall($callback, $params, $fallback);
    }

    public function incFailCount(){
        $this->failCounter++;
    }

    public function incSuccessCount(){
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
        if($fallback == null){
            return false;
        }
        return call_user_func($fallback);
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

class CircuitBreakerManager
{
    public $cricuitBreakerList = [];

    public function init(){
        $sersvice = [];
        foreach ($sersvice as $name => $config){

        }
    }

    public function getCricuitBreaker()
    {
        return new CircuitBreaker();
    }


}

//$c = new CircuitBreaker();
//
//function call($service, $uri, $prams, $fallback = null){
//    $c = new CircuitBreaker();
//    $r = new RpcClient();
//    $params = [
//        $service,
//        $uri,
//    ];
//
//    $c->call("user", [$r, "rpcCall"], $params, $fallback);
//}
//
//function post($service, $url, $params, $fallback = null){
//    /* @var $c CircuitBreaker*/
//    $c = ApplicationContext::getBean('circuitBreakerManager');
//    $c->call("user", "post", $params, $fallback);
//}
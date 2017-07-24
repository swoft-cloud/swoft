<?php

namespace swoft\pool;

use swoft\App;

/**
 *
 *
 * @uses      ConnectPool
 * @version   2017年06月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class ConnectPool implements Pool
{
    /**
     * 轮询
     */
    const ROUND_ROBIN = "roundRobin";

    /**
     * 随机
     */
    const RANDOM_SELECT = "randomSelect";

    /**
     * 一致哈希
     */
    const HASH_SELECT = "hashSelect";

    /**
     * 压力最小
     */
    const CALL_LEAST = "callLeast";

    public $serviceName = "";

    public $maxIdel = 6;
    public $maxActive = 50;
    public $maxWait = 100;
    /**
     * @var int 单位毫秒
     */
    public $timeout = 200;

    /**
     * @var bool
     */
    public $useProvider = false;

    /**
     * @var string
     */
    public $uri = "";

    /**
     * @var int 负载均衡策略
     */
    public $balancer = self::ROUND_ROBIN;

    /**
     * @var int
     */
    public $currentCounter = 0;

    /**
     * @var \SplQueue
     */
    public $queue = null;

    use Balancer;

    public function __construct($useProvider, $maxIdel, $maxActive, $maxWait, $timeout, $uri, $balancer)
    {
        $this->uri = $uri;
        $this->maxIdel = $maxIdel;
        $this->maxWait = $maxWait;
        $this->timeout = $timeout;
        $this->balancer = $balancer;
        $this->maxActive = $maxActive;
        $this->useProvider = $useProvider;

        $this->queue = new \SplQueue();
    }

    public function getConnect()
    {
        $connect = null;
        if($this->currentCounter > $this->maxActive){
            return null;
        }
        if(!$this->queue->isEmpty()){
            $connect = $this->queue->shift();
            return $connect;
        }

        $connect = $this->createConnect();
        if($connect !== null){
            $this->currentCounter++;
        }
        return $connect;

    }

    public function release($connect)
    {
        if($this->queue->count() < $this->maxActive){
            $this->queue->push($connect);
            $this->currentCounter--;
        }
    }

    public function initConnect()
    {
        for ($i = 0; $i < $this->maxIdel; $i++) {
            $connect = $this->createConnect();
            $this->queue->push($connect);
        }
    }
    abstract public function createConnect();
    abstract public function reConnect($client);
}
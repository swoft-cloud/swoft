<?php

namespace swoft\pool;

use swoft\pool\balancer\IBalancer;

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

    protected $serviceName = "";

    protected $maxIdel = 6;
    protected $maxActive = 50;
    protected $maxWait = 100;

    /**
     * @var int 单位毫秒
     */
    protected $timeout = 200;

    /**
     * @var bool
     */
    protected $useProvider = false;

    /**
     * @var string
     */
    protected $uri = "";


    /**
     * @var int
     */
    protected $currentCounter = 0;

    /**
     * @var \SplQueue
     */
    protected $queue = null;

    /**
     * @var IBalancer
     */
    protected $balancer = null;

    public function getConnect()
    {
        if($this->queue == null){
            $this->queue = new \SplQueue();
        }

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

    /**
     * @param IBalancer $balancer
     */
    public function setBalancer(IBalancer $balancer)
    {
        $this->balancer = $balancer;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName(string $serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @param bool $useProvider
     */
    public function setUseProvider(bool $useProvider)
    {
        $this->useProvider = $useProvider;
    }


    /**
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
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
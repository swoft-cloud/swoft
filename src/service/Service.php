<?php

namespace swoft\service;

use swoft\base\ApplicationContext;
use swoft\circuit\CircuitBreaker;
use swoft\circuit\CircuitBreakerManager;
use swoft\pool\ConnectPool;
use swoft\pool\ManagerPool;
use swoft\pool\ServicePool;
use swoft\helpers\RpcHelper;
use swoft\Swf;


/**
 *
 *
 * @uses      ServicePool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Service
{
    /**
     * @var \Swoole\Coroutine\Client
     */
    public $client = null;
    /**
     * @var CircuitBreaker
     */
    public $criuitBreaker = null;
    public $serviceName = "";
    /**
     * @var ConnectPool
     */
    public $connectPool = null;
    public $uri = "";
    public $params = [];
    public $fallback = null;

    public function __construct($serviceName)
    {
        $cricuitBreakerManager = Swf::getCricuitBreakerManager();
        $this->criuitBreaker = $cricuitBreakerManager->getCricuitBreaker($serviceName);
        $this->serviceName = $serviceName;
    }

    public function call($uri, array $params, $fallback = null){

        $this->uri = $uri;
        $this->params = $params;
        $this->fallback = $fallback;

        $mangerPool = Swf::getMangerPool();
        $this->connectPool = $mangerPool->getPool($this->serviceName);
        $this->client = $this->connectPool->getConnect();

        $this->client->send("stelin boy");
        $this->client->recv();

        $packData = RpcHelper::rpcPack($uri, $params);
        $this->client->send($packData);
        $this->client->recv();

//        $this->criuitBreaker->call([$this->client, 'send'], [$packData]);
//        return new Result($this);
        return "";
    }
}

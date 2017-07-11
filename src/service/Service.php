<?php

namespace swoft\service;

use swoft\base\ApplicationContext;
use swoft\circuit\CircuitBreaker;
use swoft\circuit\CircuitBreakerManager;
use swoft\pool\ConnectPool;
use swoft\pool\ManagerPool;
use swoft\pool\ServicePool;
use swoft\helpers\RpcHelper;
use swoft\App;


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
    public static function call($serviceName, $func, array $params, $fallback = null){

        $cricuitBreakerManager = App::getCricuitBreakerManager();

        /* @var $criuitBreaker CircuitBreaker*/
        $criuitBreaker = $cricuitBreakerManager->getCricuitBreaker($serviceName);

        $mangerPool = App::getMangerPool();
        $connectPool = $mangerPool->getPool($serviceName);

        /* @var $client \Swoole\Coroutine\Client*/
        $client = $connectPool->getConnect();

        $packData = RpcHelper::rpcPack($func, $params);
        $criuitBreaker->call([$client, 'send'], [$packData]);

        $result = $client->recv();
        $connectPool->release($client);
        return $result;
    }
}

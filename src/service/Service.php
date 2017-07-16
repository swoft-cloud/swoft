<?php

namespace swoft\service;

use swoft\App;
use swoft\circuit\CircuitBreaker;
use swoft\helpers\RpcHelper;

/**
 *
 *
 * @uses      InnerService
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Service
{
    /**
     * @param string   $serviceName 服务名称，例如:user
     * @param string   $func        调用函数，例如:User::getUserInfo
     * @param array    $params      函数参数，数组参数[1,2]
     * @param callable $fallback    降级处理，例如:[class,'getDefaultUserInfo']
     *
     * @return array
     */
    public static function call(string $serviceName,string $func, array $params, callable $fallback = null){

        $profileKey = "$serviceName->".$func;
        $cricuitBreakerManager = App::getCricuitBreakerManager();

        /* @var $criuitBreaker CircuitBreaker*/
        $criuitBreaker = $cricuitBreakerManager->getCricuitBreaker($serviceName);

        $mangerPool = App::getMangerPool();
        $connectPool = $mangerPool->getPool($serviceName);

        /* @var $client \Swoole\Coroutine\Client*/
        $client = $connectPool->getConnect();
        $packer = App::getPacker();
        $data = $packer->formatData($func, $params);
        $packData = $packer->pack($data);
        $result = $criuitBreaker->call([$client, 'send'], [$packData], $fallback);

        // 错误处理
        if($result === null || $result === false){
            return null;
        }

        App::profileStart($profileKey);
        $result = $client->recv();
        App::profileEnd($profileKey);
        $connectPool->release($client);

        $result = $packer->unpack($result);
        $datta = $packer->checkData($result);
        return $datta;
    }

    /**
     * @param string   $serviceName 服务名称，例如:user
     * @param string   $func        调用函数，例如:User::getUserInfos
     * @param array    $params      函数参数，数组参数[1,2]
     * @param callable $fallback    降级处理，例如:[class,'getDefaultUserInfo']
     *
     * @return ServiceResult
     */
    public static function deferCall($serviceName, $func, array $params, $fallback = null){

        $profile = "$serviceName->".$func;
        $cricuitBreakerManager = App::getCricuitBreakerManager();

        /* @var $criuitBreaker CircuitBreaker*/
        $criuitBreaker = $cricuitBreakerManager->getCricuitBreaker($serviceName);

        $mangerPool = App::getMangerPool();
        $connectPool = $mangerPool->getPool($serviceName);

        /* @var $client \Swoole\Coroutine\Client*/
        $client = $connectPool->getConnect();
        $packer = App::getPacker();
        $data = $packer->formatData($func, $params);
        $packData = $packer->pack($data);
        $result = $criuitBreaker->call([$client, 'send'], [$packData], $fallback);

        return new ServiceResult($connectPool, $client, $profile, $result);
    }
}
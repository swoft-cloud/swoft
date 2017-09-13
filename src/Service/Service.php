<?php

namespace Swoft\Service;

use Swoft\App;
use Swoft\Circuit\CircuitBreaker;
use Swoft\Pool\ServicePool;

/**
 * RPC服务调用
 *
 * @uses      InnerService
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Service
{
    /**
     * 快速失败，如果Client调用失败，立即返回，不会重试
     */
    const FAIL_FAST = 1;

    /**
     *  如果Client调用失败，会尝试从服务列表中选择另外一个服务器调用，直到成功或者到达重试次数
     */
    const FAIL_OVER = 2;

    /**
     * 失败重试，如果Client调用失败，会继续这个服务器重试，直到成功或者到达重试次数
     */
    const FAIL_TRY = 3;

    /**
     * @var int 重试选择器
     */
    protected $failSelector = self::FAIL_FAST;

    /**
     * 直接调用
     *
     * @param string   $serviceName 服务名称，例如:user
     * @param string   $func        调用函数，例如:User::getUserInfo
     * @param array    $params      函数参数，数组参数[1,2]
     * @param callable $fallback    降级处理，例如:[class,'getDefaultUserInfo']
     *
     * @return mixed
     */
    public static function call(string $serviceName, string $func, array $params, callable $fallback = null)
    {

        $profileKey = "$serviceName->" . $func;

        /* @var $criuitBreaker CircuitBreaker */
        $criuitBreaker = App::getBean($serviceName . "Breaker");

        /* @var  $connectPool ServicePool */
        $connectPool = App::getBean($serviceName . "Pool");

        /* @var $client \Swoole\Coroutine\Client */
        $client = $connectPool->getConnect();
        $packer = App::getPacker();
        $data = $packer->formatData($func, $params);
        $packData = $packer->pack($data);
        $result = $criuitBreaker->call([$client, 'send'], [$packData], $fallback);

        // 错误处理
        if ($result === null || $result === false) {
            return null;
        }

        App::profileStart($profileKey);
        $result = $client->recv();
        App::profileEnd($profileKey);
        $connectPool->release($client);

        App::debug("RPC调用结果，Data=" . json_encode($result));
        $result = $packer->unpack($result);
        $datta = $packer->checkData($result);
        return $datta;
    }

    /**
     * 延迟收包调用，用于并发请求
     *
     * @param string   $serviceName 服务名称，例如:user
     * @param string   $func        调用函数，例如:User::getUserInfos
     * @param array    $params      函数参数，数组参数[1,2]
     * @param callable $fallback    降级处理，例如:[class,'getDefaultUserInfo']
     *
     * @return ServiceResult
     */
    public static function deferCall($serviceName, $func, array $params, $fallback = null)
    {

        $profile = "$serviceName->" . $func;

        /* @var $criuitBreaker CircuitBreaker */
        $criuitBreaker = App::getBean($serviceName . "Breaker");

        /* @var $connectPool ServicePool */
        $connectPool = App::getBean($serviceName . "Pool");

        /* @var $client \Swoole\Coroutine\Client */
        $client = $connectPool->getConnect();
        $packer = App::getPacker();
        $data = $packer->formatData($func, $params);
        $packData = $packer->pack($data);
        $result = $criuitBreaker->call([$client, 'send'], [$packData], $fallback);

        return new ServiceResult($connectPool, $client, $profile, $result);
    }
}

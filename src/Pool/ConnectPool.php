<?php

namespace Swoft\Pool;

use Swoft\App;
use Swoft\Pool\Balancer\IBalancer;
use Swoft\Service\ServiceProvider;

/**
 * 通用连接池
 *
 * @uses      ConnectPool
 * @version   2017年06月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class ConnectPool implements Pool
{
    /**
     * @var string 服务清楚
     */
    protected $serviceName = "";

    /**
     * @var int 最大空闲连接数
     */
    protected $maxIdel = 6;

    /**
     * @var int 最大活跃连接数
     */
    protected $maxActive = 50;

    /**
     * @var int 最大等待连接数
     */
    protected $maxWait = 100;

    /**
     * @var int 单位毫秒
     */
    protected $timeout = 200;

    /**
     * @var bool 是否使用第三方服务发现
     */
    protected $useProvider = false;

    /**
     * @var string 有效连接地址，多个逗号分开"127.0.0.1:88,127.0.0.1:89"
     */
    protected $uri = "";

    /**
     * @var int 当前连接数
     */
    protected $currentCounter = 0;

    /**
     * @var \SplQueue 连接队列
     */
    protected $queue = null;

    /**
     * @var IBalancer 负载均衡，useProvider=true有效
     */
    protected $balancer = null;

    /**
     * @var ServiceProvider 第三服务发现，useProvider=true有效
     */
    protected $serviceprovider = null;

    /**
     * 连接池中取一个连接
     *
     * @return object|null
     */
    public function getConnect()
    {
        if ($this->queue == null) {
            $this->queue = new \SplQueue();
        }

        $connect = null;
        if ($this->currentCounter > $this->maxActive) {
            return null;
        }
        if (!$this->queue->isEmpty()) {
            $connect = $this->queue->shift();
            return $connect;
        }

        $connect = $this->createConnect();
        if ($connect !== null) {
            $this->currentCounter++;
        }
        return $connect;

    }

    /**
     * 释放一个连接到连接池
     *
     * @param object $connect 连接
     */
    public function release($connect)
    {
        if ($this->queue->count() < $this->maxActive) {
            $this->queue->push($connect);
            $this->currentCounter--;
        }
    }

    /**
     * 获取一个连接串
     *
     * @return string 如:"127.0.0.1:88"
     */
    protected function getConnectAddress()
    {
        $serviceList = $this->getServiceList();
        return $this->balancer->select($serviceList);
    }

    /**
     * 获取一个可以用服务列表
     *
     * @return array
     * <pre>
     * [
     *   "127.0.0.1:88",
     *   "127.0.0.1:88"
     * ]
     * </pre>
     */
    protected function getServiceList()
    {
        if ($this->useProvider) {
            return $this->serviceprovider->getServiceList($this->serviceName);
        }

        if (empty($this->uri)) {
            App::error($this->serviceName."服务，没有配置uri");
            throw new \InvalidArgumentException($this->serviceName."服务，没有配置uri");
        }
        return explode(',', $this->uri);
    }

    abstract public function createConnect();

    abstract public function reConnect($client);
}
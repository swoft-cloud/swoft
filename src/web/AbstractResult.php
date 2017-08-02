<?php

namespace swoft\web;

use swoft\App;
use swoft\pool\ConnectPool;

/**
 * 基类结果
 *
 * @uses      AbstractResult
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractResult implements IResult
{
    /**
     * @var ConnectPool 连接池
     */
    protected $connectPool;

    /**
     * @var mixed 连接client
     */
    protected $client;

    /**
     * @var string 缓存性能统计KEY
     */
    protected $profileKey;

    /**
     * @var bool 延迟请求是否发送成功
     */
    protected $sendResult = true;

    /**
     * AbstractResult constructor.
     *
     * @param ConnectPool $connectPool
     * @param mixed       $client
     * @param string      $profileKey
     * @param bool        $result
     */
    public function __construct($connectPool, $client, string $profileKey, $result)
    {
        $this->connectPool = $connectPool;
        $this->client = $client;
        $this->profileKey = $profileKey;
        $this->sendResult = $result;
    }

    /**
     * 延迟收包
     *
     * @param bool $defer 是否是延迟收包
     *
     * @return mixed
     */
    public function recv($defer = false)
    {
        App::profileStart($this->profileKey);
        $result = $this->client->recv();
        App::profileEnd($this->profileKey);

        // 重置延迟设置
        if($defer){
            $this->client->setDefer(false);
        }
        $this->connectPool->release($this->client);
        return $result;
    }
}
<?php

namespace swoft\service;

use swoft\App;
use swoft\circuit\CircuitBreaker;
use swoft\pool\ConnectPool;


/**
 *
 *
 * @uses      ServicePool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Result
{
    private $connectPool;
    private $client;
    private $profileKey;


    public function __construct(ConnectPool $connectPool, \Swoole\Coroutine\Client $client, $profileKey)
    {
        $this->connectPool = $connectPool;
        $this->client = $client;
        $this->profileKey = $profileKey;
    }

    public function getResult()
    {
        App::profileStart($this->profileKey);
        $result = $this->client->recv();
        App::profileEnd($this->profileKey);
        $this->connectPool->release($this->client);
        return $result;
    }
}
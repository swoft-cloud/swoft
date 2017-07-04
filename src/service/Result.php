<?php

namespace swoft\service;

use swoft\circuit\CircuitBreaker;


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
    private $service = null;
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function getResult()
    {
        $criuitBreaker = $this->service->criuitBreaker;
        $client = $this->service->client;
        $fallback = $this->service->fallback;
        $result = $criuitBreaker->call([$client, 'recv'], [], $fallback);
        $this->service->connectPool->release($client);

        return $result;
    }
}
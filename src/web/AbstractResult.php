<?php

namespace swoft\web;

use swoft\App;
use swoft\pool\ConnectPool;

/**
 *
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
     * @var ConnectPool
     */
    protected $connectPool;
    protected $client;
    /**
     * @var string
     */
    protected $profileKey;

    protected $sendResult = true;


    public function __construct($connectPool, $client, $profileKey, $result)
    {
        $this->connectPool = $connectPool;
        $this->client = $client;
        $this->profileKey = $profileKey;
        $this->sendResult = $result;
    }

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
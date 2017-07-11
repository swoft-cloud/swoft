<?php

namespace swoft\pool;

use swoft\App;

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
    public $maxIdel = 6;
    public $maxActive = 50;
    public $currentCounter = 0;
    /**
     * @var int 单位毫秒
     */
    public $timeout = 200;

    /**
     * @var \SplQueue
     */
    public $queue = null;

    public function __construct($maxIdel, $maxActive, $timeout)
    {
        $this->maxIdel = $maxIdel;
        $this->timeout = $timeout;
        $this->maxActive = $maxActive;
        $this->queue = new \SplQueue();
    }

    public function getConnect()
    {
        $connect = null;
        if($this->currentCounter > $this->maxActive){
            return false;
        }
        if(!$this->queue->isEmpty()){
            $connect = $this->queue->shift();
            return $connect;
        }

        $connect = $this->createConnect();
        $this->currentCounter++;
        return $connect;

    }

    public function release($connect)
    {
        if($this->queue->count() < $this->maxActive){
            $this->queue->push($connect);
            $this->currentCounter--;
        }
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
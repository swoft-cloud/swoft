<?php

namespace swoft\connect;

use swoft\Swf;

/**
 *
 *
 * @uses      ConnectPool
 * @version   2017年06月15日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class ConnectPool implements Pool
{

    public $max = 66;
    public $size = 6;
    public $model = 1;
    public $count = 0;

    /**
     * @var \SplQueue
     */
    public $queue = null;

    public function initConnect()
    {
        for ($i = 0; $i < $this->size; $i++) {
            $connect = $this->createConnect();
            $this->queue->push($connect);
        }
    }

    public function getConnect()
    {

        $connect = null;
        if($this->queue->isEmpty()){
            $this->initConnect();
        }
//        if ($this->queue->count() > 0) {
//            $connect = $this->queue->pop();
//            if ($connect->isConnected() == false) {
//                $connect = $this->reConnect($connect);
//            }
//
//            return $connect;
//        }

        $connect = $this->createConnect();
        return $connect;

    }


    public function release($connect)
    {
        if($this->queue->count() < $this->max){
            $this->queue->push($connect);
        }
        echo "release-----------------------------------------------------------count=".$this->queue->count()," size=".$this->size;
    }

    abstract public function createConnect();
    abstract public function reConnect($client);
}
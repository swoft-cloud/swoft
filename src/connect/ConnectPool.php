<?php

namespace swoft\connect;

use swoft\Swf;

/**
 *
 *
 * @uses      ConnectPool
 * @version   2017年06月15日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 北京尤果网文化传媒有限公司
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class ConnectPool implements Pool
{

    public $max = 50;
    public $size = 20;
    public $model = 1;
    public $count = 0;

    /**
     * @var \SplQueue
     */
    public $queue = null;

    public function getConnect()
    {
        $this->count++;
        $connect = null;
        if($this->queue->isEmpty() == false){
            $connect = $this->queue->pop();
            if($connect->isConnected() == false){
                $connect = $this->reConnect($connect);
            }
            echo "retry connect------------------------------------------";
        }else{
            echo "new connect------------------------------------------".$this->queue->count();
            $connect = $this->createConnect();
        }

        return $connect;

    }


    public function release($connect)
    {
        echo "release-----------------------------------------------------------";
        $this->queue->push($connect);
    }

    abstract public function createConnect();
    abstract public function reConnect($client);
}
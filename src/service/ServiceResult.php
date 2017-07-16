<?php

namespace swoft\service;

use swoft\App;
use swoft\circuit\CircuitBreaker;
use swoft\pool\ConnectPool;
use swoft\web\AbstractResult;
use swoft\web\IResult;


/**
 *
 *
 * @uses      ServicePool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServiceResult extends AbstractResult
{
    public function getResult()
    {
        if($this->sendResult === null || $this->sendResult === false){
            return null;
        }
        $result = $this->recv();
        $packer = App::getPacker();
        $result = $packer->unpack($result);
        $data = $packer->checkData($result);
        return $data;
    }
}
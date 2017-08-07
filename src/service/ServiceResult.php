<?php

namespace swoft\service;

use swoft\App;
use swoft\web\AbstractResult;


/**
 * RPC结果集
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
        if ($this->sendResult === null || $this->sendResult === false) {
            return null;
        }
        $result = $this->recv();

        App::debug("RPC调用结果，data=" . json_encode($result));
        $packer = App::getPacker();
        $result = $packer->unpack($result);
        $data = $packer->checkData($result);
        return $data;
    }
}
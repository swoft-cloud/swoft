<?php

namespace Swoft\Pool;

use Swoft\Service\ServiceConnect;

/**
 * RPC服务连接池
 *
 * @uses      ServicePool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServicePool extends ConnectPool
{
    /**
     * 创建连接
     *
     * @return ServiceConnect
     */
    public function createConnect()
    {
        return new ServiceConnect($this);
    }

    public function reConnect($client)
    {
        list($host, $port) = $this->getConnectAddress();
    }
}

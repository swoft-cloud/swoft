<?php

namespace swoft\pool;

use swoft\App;

/**
 *
 *
 * @uses      ServicePool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServicePool extends ConnectPool
{
    public function createConnect()
    {
        $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);

        $address = $this->getConnectAddress();
        list($host, $port) = explode(":", $address);
        if (!$client->connect($host, $port, $this->timeout))
        {
            App::error("service connect fail errorCode=".$client->errCode." host=".$host." port=".$port);
            return null;
        }

        return $client;
    }

    public function reConnect($client)
    {
        list($host, $port) = $this->getConnectAddress();
    }

    public function getConnectAddress()
    {
        $balancer = $this->balancer;
        if(!method_exists($this, $balancer)){

        }
        $serviceList = $this->getServiceList();

        return $this->$balancer($serviceList);
    }

    public function getServiceList()
    {
        if($this->useProvider){
            return App::getServiceProvider()->getServiceList($this->serviceName);
        }

        if(empty($this->uri)){

        }

        return explode(',', $this->uri);
    }
}
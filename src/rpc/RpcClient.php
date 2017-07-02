<?php

namespace swoft\rpc;

use swoft\connect\ClientPool;
use swoft\helpers\RpcHelper;
use swoft\Swf;

/**
 *
 *
 * @uses      RpcClient
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RpcClient
{
    const MSG_SERVICE = "msg";
    const USER_SERVICE = "user";

    private $pools = [];
    public $services = [];

    public function init()
    {
        foreach ($this->services as $name => $service){
            $host = $service['host'];
            $port = $service['port'];
            $size = $service['size'];

            $this->pools[$name] = new ClientPool($host, $port, $size);
        }
    }

    public function rpcCall($service, $uri, $params)
    {
        /* @var ClientPool $client*/
        $client = $this->pools[$service];
        $server = $client->getConnect();
        $server->send(RpcHelper::rpcPack($uri, $params));
        $data = $server->recv();

        $count = Swf::$app->count + 1;
        Swf::$app->count = $count;

//        $server->close();
        $client->release($server);

        return $data;
    }

    public function httpCall($host, $port = 80)
    {

    }

    public function calls($service, array $rpcs, array $https){

    }

}
<?php

namespace App\Controllers;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Rpc\Client\Service\Service;

/**
 * rpc controller test
 *
 * @Controller(prefix="rpc")
 */
class RpcController
{
    /**
     * @RequestMapping(route="call")
     * @return array
     */
    public function call()
    {
        $result = Service::call("user", 'User::getUserInfo', [2, 6, 8]);
        return ['call', $result];
    }

    /**
     * @RequestMapping("validate")
     */
    public function validate()
    {
        $result = Service::call("user", 'User::getUser', [1,2,'boy', '1.3']);
        return ['validator', $result];
    }


    /**
     * @RequestMapping("pm")
     */
    public function parentMiddleware()
    {
        $result = Service::call("user", 'Md::pm');

        return ['validator', $result];
    }

    /**
     * @RequestMapping("fm")
     */
    public function funcMiddleware()
    {
        $result = Service::call("user", 'Md::fm');

        return ['validator', $result];
    }
}
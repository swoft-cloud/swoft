<?php

namespace App\Pool;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use App\Pool\Config\UserPoolConfig;
use Swoft\Rpc\Client\Pool\ServicePool;

/**
 * the pool of user service
 *
 * @Pool(name="user")
 */
class UserServicePool extends ServicePool
{
    /**
     * @Inject()
     *
     * @var UserPoolConfig
     */
    protected $poolConfig;
}
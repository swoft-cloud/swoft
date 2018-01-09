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
 *
 * @uses      UserServicePool
 * @version   2017年12月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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
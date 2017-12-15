<?php

namespace App\Pool;

use Swoft\Bean\Annotation\Pool;
use Swoft\Pool\ServicePool;

/**
 * the pool of user service
 *
 * @Pool(name="user")
 * @uses      UserServicePool
 * @version   2017年12月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserServicePool extends ServicePool
{
    /**
     * the maximum number of idle connections
     *
     * @var int
     */
    protected $maxIdel = 6;

    /**
     * the maximum number of active connections
     *
     * @var int
     */
    protected $maxActive = 50;

    /**
     * the maximum number of wait connections
     *
     * @var int
     */
    protected $maxWait = 100;

    /**
     * the time of connect timeout
     *
     * @var int
     */
    protected $timeout = 200;

    /**
     * the addresses of connection
     *
     * <pre>
     * [
     *  '127.0.0.1:88',
     *  '127.0.0.1:88'
     * ]
     * </pre>
     *
     * @var array
     */
    protected $uri = [];
}
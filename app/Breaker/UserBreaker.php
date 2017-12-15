<?php

namespace App\Breaker;

use Swoft\Bean\Annotation\Breaker;
use Swoft\Circuit\CircuitBreaker;

/**
 * the breaker of user
 *
 * @Breaker("user")
 * @uses      UserBreaker
 * @version   2017年12月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserBreaker extends CircuitBreaker
{
    /**
     * The number of successive failures
     * If the arrival, the state switch to open
     *
     * @var int
     */
    protected $swithToFailCount = 6;

    /**
     * The number of successive successes
     * If the arrival, the state switch to close
     *
     * @var int
     */
    protected $swithToSuccessCount = 6;

    /**
     * Switch close to open delay time
     * The unit is milliseconds
     *
     * @var int
     */
    protected $delaySwithTimer = 5000;
}
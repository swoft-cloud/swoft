<?php

namespace App\Breaker;

use Swoft\Bean\Annotation\Breaker;
use Swoft\Bean\Annotation\Value;
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
     * 连续失败次数，如果到达，状态切换为open
     *
     * @var int
     */
    protected $swithToFailCount = 6;

    /**
     * 连续成功次数，如果到达，状态切换为close
     * @Value("${a.b.c}")
     * @Value(env="a.b.c")
     * @var int
     */
    protected $swithToSuccessCount = 6;

    /**
     * 单位毫秒
     *
     * @var int
     */
    protected $delaySwithTimer = 5000;


    public function fallback($fallback = null)
    {

    }
}
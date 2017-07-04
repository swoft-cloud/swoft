<?php

namespace swoft\circuit;

/**
 *
 *
 * @uses      AbstractCircuitBreaker
 * @version   2017年07月04日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class AbstractCircuitBreaker
{
    /**
     * @var int 连续失败次数，如果到达，状态切换为open
     */
    public $swithToFailCount = 6;

    /**
     * @var int 连续成功次数，如果到达，状态切换为close
     */
    public $swithToSuccessCount = 6;

    /**
     * @var int 单位毫秒
     */
    public $delaySwithTimer = 5000;
}
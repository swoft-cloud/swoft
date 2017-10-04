<?php

namespace Swoft\Base;

/**
 *
 *
 * @uses      Context
 * @version   2017年10月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Context
{
    /**
     * worker
     */
    const WORKER = 1;

    /**
     * task
     */
    const TASK = 2;

    /**
     * 自定义进程
     */
    const PROCESS = 3;

    private static $status = self::WORKER;

    /**
     * @return int
     */
    public static function getStatus(): int
    {
        return self::$status;
    }

    /**
     * @param int $status
     */
    public static function setStatus(int $status)
    {
        self::$status = $status;
    }
}
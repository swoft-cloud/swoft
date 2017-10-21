<?php

namespace Swoft\Process;

/**
 *
 *
 * @uses      AbstractProcess
 * @version   2017年10月21日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractProcess implements IProcess
{
    protected $inout = false;
    protected $pipe = true;

    protected $logg = 1;

    /**
     * @return bool
     */
    public function isInout(): bool
    {
        return $this->inout;
    }

    /**
     * @return bool
     */
    public function isPipe(): bool
    {
        return $this->pipe;
    }

    /**
     * @return int
     */
    public function getFlushInterval(): int
    {
        return $this->flushInterval;
    }

    public function isReady()
    {
        return true;
    }
}
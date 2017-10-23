<?php

namespace Swoft\Bean\Annotation;

/**
 *
 * @Annotation
 * @Target({"CLASS","METHOD"})
 * @uses      Log
 * @version   2017年10月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Log
{
    private $flushInterval = 3;

    /**
     * Log constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->flushInterval = $values['value'];
        }

        if (isset($values['flushInterval'])) {
            $this->flushInterval = $values['flushInterval'];
        }
    }

    /**
     * @return int|mixed
     */
    public function getFlushInterval()
    {
        return $this->flushInterval;
    }
}
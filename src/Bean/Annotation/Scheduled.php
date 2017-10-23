<?php

namespace Swoft\Bean\Annotation;

/**
 * scheduled注解
 *
 * @Annotation
 * @Target({"METHOD"})
 *
 * @uses      Scheduled
 * @version   2017年09月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Scheduled
{
    /**
     * @var string
     */
    private $cron;

    /**
     * Bean constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->cron = $values['value'];
        }
        if (isset($values['cron'])) {
            $this->cron = $values['cron'];
        }
    }

    /**
     * @return string
     */
    public function getCron(): string
    {
        return $this->cron;
    }

}
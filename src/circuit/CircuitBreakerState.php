<?php

namespace swoft\circuit;

/**
 * 熔断器状态close open half-open
 *
 * @uses      CircuitBreakerState
 * @version   2017年07月02日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class CircuitBreakerState
{
    /**
     * @var CircuitBreaker 熔断器
     */
    protected $circuitBreaker = null;

    public function __construct(CircuitBreaker $circuitBreaker)
    {
        $this->circuitBreaker = $circuitBreaker;
        $this->circuitBreaker->initCounter();
    }

    abstract function doCall($callback, $params= [], $fallback = null);
}
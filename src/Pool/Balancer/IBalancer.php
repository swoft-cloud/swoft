<?php

namespace Swoft\Pool\Balancer;

/**
 *
 *
 * @uses      IBalancer
 * @version   2017年07月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IBalancer
{
    public function select(array $serviceList, ...$params);
}

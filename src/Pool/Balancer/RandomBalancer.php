<?php

namespace Swoft\Pool\Balancer;

use Swoft\Di\Annotation\Bean;

/**
 * 随机选取负责
 *
 * @Bean("randomBalancer")
 * @uses      RandomBalancer
 * @version   2017年07月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RandomBalancer implements IBalancer
{
    public function select(array $serviceList, ...$params)
    {
        $randIndex = array_rand($serviceList);
        return $serviceList[$randIndex];
    }
}

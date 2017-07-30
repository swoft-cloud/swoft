<?php

namespace swoft\pool\balancer;

/**
 *
 *
 * @uses      RoundRobinBalancer
 * @version   2017年07月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RoundRobinBalancer implements IBalancer
{
    private $lastIndex = 0;

    public function select(array $serviceList, ...$params)
    {
        $currentIndex = $this->lastIndex + 1;
        if($currentIndex+1 > count($serviceList)){
            $currentIndex = 0;
        }

        $this->lastIndex = $currentIndex;
        return $serviceList[$currentIndex];
    }
}
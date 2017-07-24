<?php

namespace swoft\pool;

/**
 *
 *
 * @uses      Balancer
 * @version   2017年07月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
trait Balancer
{
    public $lastIndex = 0;

    /**
     * 随机选取节点
     *
     * @param array $serviceList
     *
     * @return string
     */
    public function randomSelect(array $serviceList)
    {
        $randIndex = array_rand($serviceList);
        return $serviceList[$randIndex];
    }

    /**
     * 轮询选取节点
     *
     * @param array $serviceList
     *
     * @return string
     */
    public function roundRobin(array $serviceList)
    {
        $currentIndex = $this->lastIndex + 1;
        if($currentIndex+1 > count($serviceList)){
            $currentIndex = 0;
        }

        $this->lastIndex = $currentIndex;
        return $serviceList[$currentIndex];
    }
}
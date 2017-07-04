<?php

namespace swoft\pool;

/**
 *
 *
 * @uses      ConnectPool
 * @version   2017年06月15日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class ManagerPool
{
    private $pools = [];
    public $services = [];

    public function init()
    {
        foreach ($this->services as $name => $service){
            $maxIdel = $service['maxIdel'];
            $maxActive = $service['maxActive'];
            $timeout = $service['timeout'];

            $poolNameClass = ServicePool::class;
            if(isset($service['class'])){
                $poolNameClass = $service['class'];
            }
            $this->pools[$name] = new $poolNameClass($maxIdel, $maxActive, $timeout);
        }
    }

    /**
     * @param $serviceName
     *
     * @return ConnectPool
     * @throws \Exception
     */
    public function getPool($serviceName)
    {
        if(!isset($this->pools[$serviceName])){
            throw new \Exception();
        }
        return $this->pools[$serviceName];
    }
}
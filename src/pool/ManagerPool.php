<?php

namespace swoft\pool;

/**
 *
 *
 * @uses      ConnectPool
 * @version   2017年06月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ManagerPool
{
    const DEFAULT_MAX_IDEL = 10;
    const DEFAULT_MAX_ACTIVE = 50;
    const DEFAULT_MAX_WAIT = 100;
    const DEFAULT_TIME_OUT = 500;
    const DEFAULT_URI = "127.0.0.1:8099";
    const DEFAULT_BALANCER = ConnectPool::RANDOM_SELECT;

    private $pools = [];
    public $services = [];
    public $useProvider = false;

    public function init()
    {
        foreach ($this->services as $name => $service) {
            $maxIdel = $service['maxIdel']?? self::DEFAULT_MAX_IDEL;
            $maxActive = $service['maxActive']?? self::DEFAULT_MAX_ACTIVE;
            $maxWait = $service['maxWait']?? self::DEFAULT_MAX_WAIT;
            $timeout = $service['timeout']?? self::DEFAULT_TIME_OUT;
            $uri = $service['uri']?? self::DEFAULT_URI;
            $balancer = $service['balancer']?? self::DEFAULT_BALANCER;

            $poolNameClass = ServicePool::class;
            if (isset($service['class'])) {
                $poolNameClass = $service['class'];
            }

            $this->pools[$name] = new $poolNameClass($this->useProvider, $maxIdel, $maxActive, $maxWait, $timeout, $uri, $balancer);
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
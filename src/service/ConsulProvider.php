<?php

namespace swoft\service;

/**
 *
 *
 * @uses      ConsulProvider
 * @version   2017年07月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ConsulProvider implements ServiceProvider
{
    private $port = 8500;
    private $address = '127.0.0.1';

    public function getServiceList(string $serviceName)
    {
        // TODO: Implement getServiceList() method.
        return [
            '127.0.0.1:8099',
            '127.0.0.1:8099',
            '127.0.0.1:8099',
            '127.0.0.1:8099',
        ];
    }

    public function registerService(string $serviceName, $host, $port, $tags = [], $interval = 10, $timeout = 1)
    {
        // TODO: Implement registerService() method.
    }
}
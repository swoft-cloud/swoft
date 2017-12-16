<?php

namespace App\Pool;

/**
 *
 *
 * @uses      UserPoolConfig
 * @version   2017年12月16日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserPoolConfig
{
    /**
     * the maximum number of idle connections
     *
     * @Value(name="${{prefix.name}.maxIdel}", env="${{prefix.env}_MAX_IDEL}")
     * @var int
     */
    protected $maxIdel = 6;

    /**
     * the maximum number of active connections
     *
     * @Value(name="${{prefix.name}.maxActive}", env="${{prefix.env}_MAX_ACTIVE}")
     * @var int
     */
    protected $maxActive = 50;

    /**
     * the maximum number of wait connections
     *
     * @Value(name="${{prefix.name}.maxWait}", env="${{prefix.env}_MAX_WAIT}")
     * @var int
     */
    protected $maxWait = 100;

    /**
     * the time of connect timeout
     *
     * @Value(name="${{prefix.name}.timeout}", env="${{prefix.env}_TIMEOUT}")
     * @var int
     */
    protected $timeout = 200;

    /**
     * the addresses of connection
     *
     * <pre>
     * [
     *  '127.0.0.1:88',
     *  '127.0.0.1:88'
     * ]
     * </pre>
     *
     * @Value(name="${{prefix.name}.uri}", env="${{prefix.env}_URI}")
     * @var array
     */
    protected $uri = [];

    /**
     * whether to user provider(consul/etcd/zookeeper)
     *
     * @Value(name="${{prefix.name}.useProvider}", env="${{prefix.env}_USE_PROVIDER}")
     * @var bool
     */
    protected $useProvider = false;

    /**
     * the default balancer is random balancer
     *
     * @Value(name="${{prefix.name}.balancer}", env="${{prefix.env}_BALANCER}")
     * @var string
     */
    protected $balancer = BalancerManager::TYPE_RANDOM;

    /**
     * the default provider is consul provider
     *
     * @Value(name="${{prefix.name}.provider}", env="${{prefix.env}_PROVIDER}")
     * @var string
     */
    protected $serviceProvider = null;



}
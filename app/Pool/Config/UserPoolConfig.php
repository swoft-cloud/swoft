<?php

namespace App\Pool\Config;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;

/**
 * the config of service user
 *
 * @Bean()
 * @uses      UserPoolConfig
 * @version   2017年12月16日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserPoolConfig
{
    /**
     * the name of pool
     *
     * @Value(name="${config.service.user.name}", env="${SERVICE_USER_NAME}")
     * @var string
     */
    protected $name = "";

    /**
     * the maximum number of idle connections
     *
     * @Value(name="${config.service.user.maxIdel}", env="${SERVICE_USER_MAX_IDEL}")
     * @var int
     */
    protected $maxIdel = 6;

    /**
     * the maximum number of active connections
     *
     * @Value(name="${config.service.user.maxActive}", env="${SERVICE_USER_MAX_ACTIVE}")
     * @var int
     */
    protected $maxActive = 50;

    /**
     * the maximum number of wait connections
     *
     * @Value(name="${config.service.user.maxWait}", env="${SERVICE_USER_MAX_WAIT}")
     * @var int
     */
    protected $maxWait = 100;

    /**
     * the time of connect timeout
     *
     * @Value(name="${config.service.user.timeout}", env="${SERVICE_USER_TIMEOUT}")
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
     * @Value(name="${config.service.user.uri}", env="${SERVICE_USER_URI}")
     * @var array
     */
    protected $uri = [];

    /**
     * whether to user provider(consul/etcd/zookeeper)
     *
     * @Value(name="${config.service.user.useProvider}", env="${SERVICE_USER_USE_PROVIDER}")
     * @var bool
     */
    protected $useProvider = false;

    /**
     * the default balancer is random balancer
     *
     * @Value(name="${config.service.user.balancer}", env="${SERVICE_USER_BALANCER}")
     * @var string
     */
    protected $balancer = BalancerSelector::TYPE_RANDOM;

    /**
     * the default provider is consul provider
     *
     * @Value(name="${config.service.user.provider}", env="${SERVICE_USER_PROVIDER}")
     * @var string
     */
    protected $provider = ProviderSelector::TYPE_CONSUL;
}
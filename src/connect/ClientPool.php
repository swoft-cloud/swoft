<?php

namespace swoft\connect;

/**
 *
 *
 * @uses      ClientPool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ClientPool extends ConnectPool
{
    public $host = '127.0.0.1';
    public $port = 9501;

    /**
     * @var float sencond
     */
    public $timeout = 0.1;


    public function __construct($host, $port, $size, $timeout= 0.2)
    {
        $this->host = $host;
        $this->port = $port;
        $this->size = $size;

        $this->queue = new \SplQueue();
    }

    public function createConnect()
    {
        $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
        if (!$client->connect($this->host, $this->port, 0.5))
        {
            echo "connect failed. Error: {$client->errCode}\n $this->host $this->port";
        }

        return $client;
    }

    public function reConnect($client)
    {
        $result = $client->connect($this->host, $this->port, 0.5);
        if (!$result)
        {
            echo "connect failed. Error: {$client->errCode}\n $this->host $this->port";
        }

        return $client;
    }
}
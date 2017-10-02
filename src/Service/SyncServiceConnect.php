<?php

namespace Swoft\Service;

/**
 *
 *
 * @uses      SyncServiceConnect
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SyncServiceConnect extends AbstractServiceConnect
{
    /**
     * @var resource
     */
    protected $connect;

    public function createConnect()
    {
        $address = $this->connectPool->getConnectAddress();
        $timeout = $this->connectPool->getTimeout();
        list($host, $port) = explode(":", $address);

        $remoteSocket = sprintf('tcp://%s:%d', $host, $port);
        $fp = stream_socket_client($remoteSocket, $errno, $errstr, $timeout);
        $this->connect = $fp;
    }

    public function reConnect()
    {

    }

    /**
     * @param string $data
     *
     * @return bool
     */
    public function send(string $data): bool
    {
        return fwrite($this->connect, $data);
    }

    public function recv(): string
    {
        return fread($this->connect, 1024);
    }
}
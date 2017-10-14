<?php

namespace Swoft\Server;

/**
 * server接口
 *
 * @uses      IServer
 * @version   2017年10月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IServer
{
    public function start();

    public function stop();

    public function reload($onlyTask = false);

    public function isRunning();

    public function getServer();

    public function getTcpSetting();

    public function getHttpSetting();

    public function getServerSetting();

    public function setDaemonize();

    public function setRpcEnable();

}
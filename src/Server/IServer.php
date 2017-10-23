<?php

namespace Swoft\Server;

use Swoole\Server;

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
    /**
     * 启动
     */
    public function start();

    /**
     * 停止
     *
     * @return bool
     */
    public function stop();

    /**
     * 重载
     *
     * @param bool $onlyTask    是否只reload任务
     */
    public function reload($onlyTask = false);

    /**
     * 是否已经运行
     *
     * @return bool
     */
    public function isRunning();

    /**
     * 获取server
     *
     * @return Server
     */
    public function getServer();

    /**
     * tcp配置
     *
     * @return array
     */
    public function getTcpSetting();

    /**
     * http配置
     *
     * @return array
     */
    public function getHttpSetting();

    /**
     * server配置
     *
     * @return array
     */
    public function getServerSetting();

    /**
     * 设置守护进程
     */
    public function setDaemonize();
}
<?php

namespace Swoft\Process;

use Swoft\Server\AbstractServer;

/**
 * 抽象进程
 *
 * @uses      AbstractProcess
 * @version   2017年10月21日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractProcess implements IProcess
{
    /**
     * inout
     *
     * @var bool
     */
    protected $inout = false;

    /**
     * 是否开启通道
     *
     * @var bool
     */
    protected $pipe = true;

    /**
     * server服务对象
     *
     * @var AbstractServer
     */
    protected $server;

    /**
     * 初始化
     *
     * @param AbstractServer $server
     */
    public function __construct(AbstractServer $server)
    {
        $this->server = $server;
    }

    /**
     * 是否启用inout
     *
     * @return bool
     */
    public function isInout(): bool
    {
        return $this->inout;
    }

    /**
     * 是否启用通道
     *
     * @return bool
     */
    public function isPipe(): bool
    {
        return $this->pipe;
    }

    /**
     * 进程启动前准备工作是否完成
     *
     * @return bool
     */
    public function isReady()
    {
        return true;
    }
}
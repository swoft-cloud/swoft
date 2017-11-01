<?php

namespace Swoft\Process;

use Swoft\Server\AbstractServer;
use Swoft\Server\PipeMessage;
use Swoft\Task\Task;

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
     *
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
    public function isReady(): bool
    {
        return true;
    }

    /**
     * 投递异步任务
     *
     * @param string $taskName   任务名称
     * @param string $methodName 方法
     * @param array  $params     参数
     * @param int    $timeout    超时时间
     */
    public function task(string $taskName, string $methodName, array $params = [], $timeout = 3)
    {
        $data = [
            'name'    => $taskName,
            'method'  => $methodName,
            'params'  => $params,
            'timeout' => $timeout,
            'type'    => Task::TYPE_ASYNC
        ];

        $message = PipeMessage::pack(PipeMessage::TYPE_TASK, $data);
        $this->server->getServer()->sendMessage($message, 0);
    }
}

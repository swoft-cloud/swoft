<?php

namespace Swoft\Console\Command;

use Swoft\Console\ConsoleCommand;

/**
 * rpc server commands
 *
 * @uses      RpcController
 * @version   2017年10月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RpcController extends ConsoleCommand
{
    /**
     * 启动RPC服务器
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @options
     * -d,--d 后台启动
     *
     * @example
     * php swoft.php rpc:start -d
     */
    public function startCommand()
    {

    }

    /**
     * 重载RPC服务器
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @options
     * -t 重载任务
     *
     * @example
     * php swoft.php rpc:reload -d
     */
    public function reloadCommand()
    {

    }

    /**
     * 停止RPC服务器
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @example
     * php swoft.php rpc:stop -d
     */
    public function stopCommand()
    {

    }

    /**
     * 重启RPC服务器
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @example
     * php swoft.php rpc:restart -d
     */
    public function restartCommand()
    {

    }
}
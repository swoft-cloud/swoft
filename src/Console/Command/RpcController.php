<?php

namespace Swoft\Console\Command;

use Swoft\Console\ConsoleCommand;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Server\RpcServer;

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
     * rpc server服务器
     *
     * @var RpcServer
     */
    private $rpcServer;

    public function __construct(Input $input, Output $output)
    {
        parent::__construct($input, $output);

        $this->rpcServer = new RpcServer();
    }

    /**
     * start rpc server
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @options
     * -d,--d start by daemonize
     *
     * @example
     * php swoft.php rpc:start -d
     */
    public function startCommand()
    {
        // 是否正在运行
        if ($this->rpcServer->isRunning()) {
            $serverStatus = $this->rpcServer->getServerSetting();
            $this->output->writeln("<error>The server have been running!(PID: {$serverStatus['masterPid']})</error>", true, true);
        }

        $this->setStartArgs();
        $tcpStatus = $this->rpcServer->getTcpSetting();

        // tcp启动参数
        $tcpHost = $tcpStatus['host'];
        $tcpPort = $tcpStatus['port'];
        $tcpType = $tcpStatus['type'];
        $tcpModel = $tcpStatus['model'];

        // 信息面板
        $lines = [
            '                    Information Panel                     ',
            '**********************************************************',
            "* tcp | Host: <note>$tcpHost</note>, port: <note>$tcpPort</note>, Model: <note>$tcpModel</note>, type: <note>$tcpType</note>",
            '**********************************************************',
        ];

        $this->output->writeln(implode("\n", $lines));

        $this->rpcServer->start();
    }

    /**
     * reload rpc server
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @options
     * -t only reload tasks
     *
     * @example
     * php swoft.php rpc:reload
     */
    public function reloadCommand()
    {
        // 是否已启动
        if (!$this->rpcServer->isRunning()) {
            $this->output->writeln('<error>The server is not running! cannot reload</error>', true, true);
        }

        $this->output->writeln("<info>Server {$this->input->getFullScript()} is reloading</info>");

        // 重载
        $reloadTask = $this->input->hasOpt('t');
        $this->rpcServer->reload($reloadTask);
        $this->output->writeln("<success>Server {$this->input->getFullScript()} reload success</success>");
    }

    /**
     * stop rpc server
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @example
     * php swoft.php rpc:stop
     */
    public function stopCommand()
    {
        // 是否已启动
        if (!$this->rpcServer->isRunning()) {
            $this->output->writeln('<error>The server is not running! cannot stop</error>', true, true);
        }

        $serverStatus = $this->rpcServer->getServerSetting();
        $pidFile = $serverStatus['pfile'];

        @unlink($pidFile);
        $this->output->writeln("<info>Swoft {$this->input->getFullScript()} is stopping ...</info>");

        $result = $this->rpcServer->stop();

        // 停止失败
        if (!$result) {
            $this->output->writeln("<error>Swoft {$this->input->getFullScript()} stop fail</error>", true, true);
        }

        $this->output->writeln("<success>Swoft {$this->input->getFullScript()} stop success!</success>");
    }

    /**
     * restart rpc server
     *
     * @usage
     * rpc:{command} [arguments] [options]
     *
     * @example
     * php swoft.php rpc:restart
     */
    public function restartCommand()
    {
        // 是否已启动
        if ($this->rpcServer->isRunning()) {
            $this->stopCommand();
        }

        // 重启默认是守护进程
        $this->rpcServer->setDaemonize();
        $this->startCommand();
    }

    /**
     * 设置启动选项
     */
    private function setStartArgs()
    {
        $daemonize = $this->input->hasOpt('d');

        if ($daemonize) {
            $this->rpcServer->setDaemonize();
        }
    }
}
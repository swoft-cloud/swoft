<?php

namespace Swoft\Console\Command;

use Swoft\Console\ConsoleCommand;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Server\HttpServer;

/**
 * the group command list of http-server
 *
 * @uses      ServerController
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServerController extends ConsoleCommand
{
    /**
     * httpServer服务器
     *
     * @var HttpServer
     */
    private $httpServer;

    /**
     * 初始化
     *
     * @param Input  $input  输入
     * @param Output $output 输出
     */
    public function __construct(Input $input, Output $output)
    {
        parent::__construct($input, $output);
        $this->httpServer = new HttpServer();
    }

    /**
     * start http server
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @options
     * -d,--d start by daemonized process
     *
     * @example
     * php swoft.php server:start -d -r
     */
    public function startCommand()
    {
        // 是否正在运行
        $serverStatus = $this->httpServer->getServerSetting();
        if ($this->httpServer->isRunning()) {
            $this->output->writeln("<error>The server have been running!(PID: {$serverStatus['masterPid']})</error>", true, true);
        }

        // 启动参数
        $this->setStartArgs();
        $httpStatus = $this->httpServer->getHttpSetting();
        $tcpStatus = $this->httpServer->getTcpSetting();

        // http启动参数
        $httpHost = $httpStatus['host'];
        $httpPort = $httpStatus['port'];
        $httpModel = $httpStatus['model'];
        $httpType = $httpStatus['type'];

        // tcp启动参数
        $tcpEnable = $serverStatus['tcpable'];
        $tcpHost = $tcpStatus['host'];
        $tcpPort = $tcpStatus['port'];
        $tcpType = $tcpStatus['type'];
        $tcpEnable = $tcpEnable ? 1 : 0;

        // 信息面板
        $lines = [
            '                    Information Panel                     ',
            '**********************************************************',
            "* http | Host: <note>$httpHost</note>, port: <note>$httpPort</note>, Model: <note>$httpModel</note>, type: <note>$httpType</note>",
            "* tcp  | Enable: <note>$tcpEnable</note>, host: <note>$tcpHost</note>, port: <note>$tcpPort</note>, type: <note>$tcpType</note>",
            '**********************************************************',
        ];

        // 启动服务器
        $this->output->writeln(implode("\n", $lines));
        $this->httpServer->start();
    }

    /**
     * reload worker process
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @options
     * -t only to reload task processes, default to reload worker and task
     *
     * @example
     * php swoft.php server:reload
     */
    public function reloadCommand()
    {
        // 是否已启动
        if (!$this->httpServer->isRunning()) {
            $this->output->writeln('<error>The server is not running! cannot reload</error>', true, true);
        }

        $this->output->writeln("<info>Server {$this->input->getFullScript()} is reloading</info>");

        // 重载
        $reloadTask = $this->input->hasOpt('t');
        $this->httpServer->reload($reloadTask);
        $this->output->writeln("<success>Server {$this->input->getFullScript()} reload success</success>");
    }

    /**
     * stop http server
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @example
     * php swoft.php server:stop
     */
    public function stopCommand()
    {
        // 是否已启动
        if (!$this->httpServer->isRunning()) {
            $this->output->writeln('<error>The server is not running! cannot stop</error>', true, true);
        }

        // pid文件
        $serverStatus = $this->httpServer->getServerSetting();
        $pidFile = $serverStatus['pfile'];

        @unlink($pidFile);
        $this->output->writeln("<info>Swoft {$this->input->getFullScript()} is stopping ...</info>");

        $result = $this->httpServer->stop();

        // 停止失败
        if (!$result) {
            $this->output->writeln("<error>Swoft {$this->input->getFullScript()} stop fail</error>", true, true);
        }

        $this->output->writeln("<success>Swoft {$this->input->getFullScript()} stop success!</success>");
    }

    /**
     * restart http server
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @example
     * php swoft.php server:restart
     */
    public function restartCommand()
    {
        // 是否已启动
        if ($this->httpServer->isRunning()) {
            $this->stopCommand();
        }

        // 重启默认是守护进程
        $this->httpServer->setDaemonize();
        $this->startCommand();
    }

    /**
     * 设置启动选项，覆盖swoft.ini配置选项
     */
    private function setStartArgs()
    {
        $daemonize = $this->input->hasOpt('d');

        if ($daemonize) {
            $this->httpServer->setDaemonize();
        }
    }
}
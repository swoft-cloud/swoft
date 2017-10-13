<?php

namespace Swoft\Console\Command;

use Swoft\Console\ConsoleCommand;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Web\HttpServer;

/**
 * HTTP服务器命令集合
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

    public function __construct(Input $input, Output $output)
    {
        parent::__construct($input, $output);

        $this->httpServer = new HttpServer();
    }

    /**
     * 启动HTTP服务器
     * 启动HTTP服务器
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @options
     * -d,--d 后台启动
     * -r,--r 启动RPC服务，默认读取配置
     *
     * @example
     * php swoft.php server:start -d
     */
    public function startCommand()
    {
        // 是否正在运行
        if ($this->httpServer->isRunning()) {
            $serverStatus = $this->httpServer->getServerStatus();
            $this->output->writeln("<error>The server have been running!(PID: {$serverStatus['masterPid']})</error>", true, true);
        }

        $this->setStartArgs();
        $httpStatus = $this->httpServer->getHttpStatus();
        $tcpStatus = $this->httpServer->getTcpStatus();

        // http启动参数
        $httpHost = $httpStatus['host'];
        $httpPort = $httpStatus['port'];
        $httpModel = $httpStatus['model'];
        $httpType = $httpStatus['type'];

        // tcp启动参数
        $tcpEnable = $tcpStatus['enable'];
        $tcpHost = $tcpStatus['host'];
        $tcpPort = $tcpStatus['port'];
        $tcpType = $tcpStatus['type'];

        // 信息面板
        $lines = [
            '                    Information Panel                     ',
            '**********************************************************',
            "* http | Host: <note>$httpHost</note>, port: <note>$httpPort</note>, Model: <note>$httpModel</note>, type: <note>$httpType</note>",
            "* tcp  | Enable: <note>$tcpEnable</note>, host: <note>$tcpHost</note>, port: <note>$tcpPort</note>, type: <note>$tcpType</note>",
            '**********************************************************',
        ];

        $this->output->writeln(implode("\n", $lines));

        $this->httpServer->start();
    }

    /**
     * 重载HTTP服务器
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @options
     * -t 只重载任务
     *
     * @example
     * php swoft.php server:reload -d
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
     * 停止HTTP服务器
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @example
     * php swoft.php server:stop -d
     */
    public function stopCommand()
    {
        // 是否已启动
        if (!$this->httpServer->isRunning()) {
            $this->output->writeln('<error>The server is not running! cannot stop</error>', true, true);
        }

        $serverStatus = $this->httpServer->getServerStatus();
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
     * 重启HTTP服务器
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @example
     * php swoft.php server:restart -d
     */
    public function restartCommand()
    {
        // 是否已启动
        if ($this->httpServer->isRunning()) {
            $this->stopCommand();
        }

        // 重启默认是守护进程
        $this->httpServer->setDaemonize(1);
        $this->startCommand();
    }

    private function setStartArgs(){

        $enable = $this->input->hasOpt('r');
        $daemonize = $this->input->hasOpt('d');

        if($daemonize){
            $this->httpServer->setDaemonize();
        }
        if($enable){
            $this->httpServer->setRpcEnable();
        }
    }
}
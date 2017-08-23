<?php

namespace swoft\console;

use swoft\App;
use swoft\console\style\LiteStyle;
use swoft\di\annotation\Bean;
use swoft\di\annotation\Inject;
use swoft\web\Application;

/**
 * 启动命令行
 *
 * @Bean("console")
 * @uses      Console
 * @version   2017年08月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Console
{
    /**
     * @var string 启动脚本文件
     */
    private $scriptFile = '';

    /**
     * @var string 当前命令，如start/stop...
     */
    private $command = '';

    /**
     * @var bool 是否只reload task任务进程
     */
    private $reloadTask = false;

    /**
     * @Inject("${application}")
     * @var Application 应用
     */
    private $application = null;

    /**
     * @var array tcp启动参数
     */
    private $tcpStatus = [];

    /**
     * @var array http启动参数
     */
    private $httpStatus = [];

    /**
     * @Inject()
     * @var LiteStyle 颜色辅助
     */
    private $liteStyle = null;


    /**
     * 初始化
     */
    public function init()
    {
        // 参数解析
        $argv = $_SERVER['argv'];
        if (count($argv) >= 2) {
            list($this->scriptFile, $this->command) = $argv;
        }
        $daemonize = in_array('-d', $argv) ? 1 : 0;
        $this->reloadTask = in_array('-t', $argv);

        // 初始化数据
        $this->application->setDaemonize($daemonize);
        $this->application->setScriptFile($this->scriptFile);
        $this->tcpStatus = $this->application->getTcpStatus();
        $this->httpStatus = $this->application->getHttpStatus();
    }

    /**
     * 运行console
     */
    public function run()
    {
        if (empty($this->command) || !method_exists($this, $this->command)) {
            $this->help();
        } else {
            $this->{$this->command}();
        }
    }

    /**
     * start命令
     */
    public function start()
    {
        // 是否正在运行
        if ($this->application->isRunning()) {
            $serverStatus = $this->application->getServerStatus();
            echo $this->liteStyle->color("The server have been running!(PID: {$serverStatus['masterPid']})", LiteStyle::BG_RED) . "\n";
            exit(0);
        }

        // http启动参数，颜色处理
        $httpHost = $this->liteStyle->color($this->httpStatus['host'], LiteStyle::FG_GREEN);
        $httpPort = $this->liteStyle->color($this->httpStatus['port'], LiteStyle::FG_GREEN);
        $httpModel = $this->liteStyle->color($this->httpStatus['model'], LiteStyle::FG_GREEN);
        $httpType = $this->liteStyle->color($this->httpStatus['type'], LiteStyle::FG_GREEN);

        // tcp启动参数，颜色处理
        $tcpEnable = $this->liteStyle->color($this->tcpStatus['enable'], LiteStyle::FG_GREEN);
        $tcpHost = $this->liteStyle->color($this->tcpStatus['host'], LiteStyle::FG_GREEN);
        $tcpPort = $this->liteStyle->color($this->tcpStatus['port'], LiteStyle::FG_GREEN);
        $tcpType = $this->liteStyle->color($this->tcpStatus['type'], LiteStyle::FG_GREEN);

        // 信息面板
        $lines = [
            '                    Information Panel                     ',
            '**********************************************************',
            '* http | Host: ' . $httpHost . ', port: ' . $httpPort . ', model: ' . $httpModel . ', type: ' . $httpType,
            '* tcp  | Enable: ' . $tcpEnable . ', host: ' . $tcpHost . ', port: ' . $tcpPort . ', type: ' . $tcpType,
            '**********************************************************',
        ];

        $line = implode("\n", $lines);
        echo $line . "\n";

        $this->application->start();
    }

    /**
     * restart命令
     */
    public function restart()
    {
        // 是否已启动
        if ($this->application->isRunning()) {
            $this->stop();
        }

        // 重启默认是守护进程
        $this->application->setDaemonize(1);
        $this->start();
    }

    /**
     * reload命令
     */
    public function reload()
    {
        // 是否已启动
        if (!$this->application->isRunning()) {
            echo $this->liteStyle->color('The server is not running! cannot reload', LiteStyle::BG_RED) . "\n";
            exit(0);
        }

        echo "Server {$this->scriptFile} is reloading \n";

        // 重载
        $this->application->reload($this->reloadTask);

        echo $this->liteStyle->color("Server {$this->scriptFile} reload success ", LiteStyle::FG_GREEN) . "\n";
    }

    /**
     * stop命令
     */
    public function stop()
    {
        // 是否已启动
        if (!$this->application->isRunning()) {
            echo $this->liteStyle->color('The server is not running! cannot stop', LiteStyle::BG_RED) . "\n";
            exit(0);
        }

        $serverStatus = $this->application->getServerStatus();
        $pidFile = $serverStatus['pfile'];

        @unlink($pidFile);
        echo("swoft {$this->scriptFile} is stopping ... \n");

        $result = $this->application->stop();

        // 停止失败
        if (!$result) {
            echo('swoft ' . $this->scriptFile . " stop fail \n");
            exit();
        }

        echo $this->liteStyle->color("swoft {$this->scriptFile} stop success", LiteStyle::FG_GREEN) . " \n";
    }

    /**
     * help命令
     */
    public function help()
    {
        // commands命令颜色处理
        $command = $this->liteStyle->color('Commands:', LiteStyle::FG_LIGHT_YELLOW);
        $start = $this->liteStyle->color("start", LiteStyle::FG_LIGHT_GREEN);
        $restart = $this->liteStyle->color("restart", LiteStyle::FG_LIGHT_GREEN);
        $reload = $this->liteStyle->color("reload", LiteStyle::FG_LIGHT_GREEN);
        $stop = $this->liteStyle->color("stop", LiteStyle::FG_LIGHT_GREEN);
        $help = $this->liteStyle->color("help", LiteStyle::FG_LIGHT_GREEN);

        // Options命令颜色处理
        $options = $this->liteStyle->color('Options:', LiteStyle::FG_LIGHT_YELLOW);
        $optD = $this->liteStyle->color('-d', LiteStyle::FG_LIGHT_GREEN);

        // 信息面板
        $lines = [
            $command,
            '  ' . $start . '    Start the swoole application server',
            '  ' . $restart . '  Restart the swoole application server',
            '  ' . $reload . '   Reload the swoole application server',
            '  ' . $stop . '     Stop the swoole application server',
            '  ' . $help . '     Show help of swoft console',
            $options,
            '  ' . $optD . '       Start with daemonize',
        ];

        $line = implode("\n", $lines);
        echo $line . "\n";
    }
}
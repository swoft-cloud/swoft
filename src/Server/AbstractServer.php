<?php

namespace Swoft\Server;

use Swoft\App;
use Swoole\Lock;
use Swoole\Server;

/**
 * 抽象server
 *
 * @uses      AbstractServer
 * @version   2017年10月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractServer implements IServer
{
    /**
     * tcp配置信息
     *
     * @var array
     */
    protected $tcpSetting = [];

    /**
     *  http配置信息
     *
     * @var array
     */
    protected $httpSetting = [];

    /**
     * server配置信息
     *
     * @var array
     */
    protected $serverSetting = [];

    /**
     * 自定义进程配置
     *
     * @var array
     */
    protected $processSetting = [];

    /**
     * swoole启动参数
     *
     * @var array
     */
    protected $setting = [];

    /**
     * server服务器
     *
     * @var Server
     */
    protected $server;

    /**
     * 启动入口文件
     *
     * @var string
     */
    protected $scriptFile;

    /**
     * worker加载锁
     *
     * @var Lock;
     */
    protected $workerLock;

    /**
     * @var
     */
    protected $status;

    /**
     * AbstractServer constructor.
     */
    public function __construct()
    {
        // 初始化App
        App::$server = $this;

        // 初始化worker锁
        $this->workerLock = new Lock(SWOOLE_RWLOCK);

        // 加载swoft.ini
        $this->loadSwoftIni();
    }

    /**
     * 加载swoft.ini配置
     */
    protected function loadSwoftIni()
    {
        $settingsPath = App::getAlias('@settings');
        $settings = parse_ini_file($settingsPath, true);
        if (!isset($settings['tcp'])) {
            throw new \InvalidArgumentException("未配置tcp启动参数，settings=" . json_encode($settings));
        }

        if (!isset($settings['http'])) {
            throw new \InvalidArgumentException("未配置http启动参数，settings=" . json_encode($settings));
        }

        if (!isset($settings['server'])) {
            throw new \InvalidArgumentException("未配置server启动参数，settings=" . json_encode($settings));
        }

        if (!isset($settings['setting'])) {
            throw new \InvalidArgumentException("未配置setting启动参数，settings=" . json_encode($settings));
        }

        if (isset($settings['setting']['log_file'])) {
            $logPath = $settings['setting']['log_file'];
            $settings['setting']['log_file'] = App::getAlias($logPath);
        }

        $this->tcpSetting = $settings['tcp'];
        $this->httpSetting = $settings['http'];
        $this->serverSetting = $settings['server'];
        $this->processSetting = $settings['process'];
        $this->setting = $settings['setting'];
    }

    /**
     * reload服务
     *
     * @param bool $onlyTask 是否只重载任务
     */
    public function reload($onlyTask = false)
    {
        $signal = $onlyTask ? SIGUSR2 : SIGUSR1;
        posix_kill($this->serverSetting['managerPid'], $signal);
    }

    /**
     * stop服务
     */
    public function stop()
    {
        $timeout = 60;
        $startTime = time();
        $this->serverSetting['masterPid'] && posix_kill($this->serverSetting['masterPid'], SIGTERM);

        $result = true;
        while (1) {
            $masterIslive = $this->serverSetting['masterPid'] && posix_kill($this->serverSetting['masterPid'], SIGTERM);
            if ($masterIslive) {
                if (time() - $startTime >= $timeout) {
                    $result = false;
                    break;
                }
                usleep(10000);
                continue;
            }

            break;
        }
        return $result;
    }

    /**
     * 服务是否已启动
     *
     * @return bool
     */
    public function isRunning()
    {
        $masterIsLive = false;
        $pFile = $this->serverSetting['pfile'];

        // pid 文件是否存在
        if (file_exists($pFile)) {
            // 文件内容解析
            $pidFile = file_get_contents($pFile);
            $pids = explode(',', $pidFile);

            $this->serverSetting['masterPid'] = $pids[0];
            $this->serverSetting['managerPid'] = $pids[1];
            $masterIsLive = $this->serverSetting['masterPid'] && @posix_kill($this->serverSetting['managerPid'], 0);
        }

        return $masterIsLive;
    }

    /**
     * 获取http server
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * 获取tcp启动参数
     *
     * @return array
     */
    public function getTcpSetting()
    {
        return $this->tcpSetting;
    }

    /**
     * 获取http启动参数
     *
     * @return array
     */
    public function getHttpSetting()
    {
        return $this->httpSetting;
    }

    /**
     * 获取启动server状态
     *
     * @return array
     */
    public function getServerSetting()
    {
        return $this->serverSetting;
    }

    /**
     * listen tcp配置
     *
     * @return array
     */
    protected function getListenTcpSetting()
    {
        $listenTcpSetting = $this->tcpSetting;
        unset($listenTcpSetting['host']);
        unset($listenTcpSetting['port']);
        unset($listenTcpSetting['model']);
        unset($listenTcpSetting['type']);

        return $listenTcpSetting;
    }

    /**
     * 设置守护进程启动
     *
     */
    public function setDaemonize()
    {
        $this->setting['daemonize'] = 1;
    }

    /**
     * 设置启动RPC
     */
    public function setRpcEnable()
    {
        $this->tcpSetting['enable'] = 1;
    }

    /**
     * Tasker进程回调
     *
     * @param Server $server   server
     * @param int    $taskId   taskId
     * @param int    $workerId workerId
     * @param mixed  $data     data
     *
     * @return mixed
     *
     */
    public function onTask(Server $server, int $taskId, int $workerId, $data)
    {
        return $data;
    }
}
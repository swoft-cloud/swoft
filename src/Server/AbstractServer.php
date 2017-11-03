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
    public $tcpSetting = [];

    /**
     *  http配置信息
     *
     * @var array
     */
    public $httpSetting = [];

    /**
     * server配置信息
     *
     * @var array
     */
    public $serverSetting = [];

    /**
     * 自定义进程配置
     *
     * @var array
     */
    public $processSetting = [];

    /**
     * 用户自定义任务定时器配置
     *
     * @var array
     */
    public $crontabSetting = [];

    /**
     * @var array
     */
    public $bootstrapItems = [];

    /**
     * swoole启动参数
     *
     * @var array
     */
    public $setting = [];

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

        // 加载启动项
        $this->bootstrap();
    }

    /**
     * 加载启动项
     *
     * @return $this
     */
    protected function bootstrap()
    {
        $defaultItems = [
            Booting\InitMbFunsEncoding::class,
            Booting\LoadEnv::class,
            Booting\LoadInitConfiguration::class,
            Booting\InitWorkerLock::class,
            Booting\InitSwoftConfig::class,
        ];
        $bootstrapItems = $this->bootstrapItems;
        $bootstrapItems = array_merge($defaultItems, $bootstrapItems);
        foreach ($bootstrapItems as $bootstrapItem) {
            if (class_exists($bootstrapItem)) {
                $itemInstance = new $bootstrapItem();
                if ($itemInstance instanceof Booting\Bootable) {
                    $itemInstance->bootstrap();
                }
            }
        }
        return $this;
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
     * @return Lock
     */
    public function getWorkerLock(): Lock
    {
        return $this->workerLock;
    }

    /**
     * @param Lock $workerLock
     */
    public function setWorkerLock(Lock $workerLock)
    {
        $this->workerLock = $workerLock;
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
     */
    public function setDaemonize()
    {
        $this->setting['daemonize'] = 1;
    }

    /**
     * pname名称
     *
     * @return string
     */
    public function getPname()
    {
        return $this->serverSetting['pname'];
    }

    /**
     * Tasker进程回调
     *
     * @param Server $server server
     * @param int $taskId taskId
     * @param int $workerId workerId
     * @param mixed $data data
     * @return mixed
     */
    public function onTask(Server $server, int $taskId, int $workerId, $data)
    {
        return $data;
    }
}

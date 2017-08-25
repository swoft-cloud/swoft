<?php

namespace swoft\web;

use swoft\App;
use swoft\base\Inotify;
use swoft\di\BeanFactory;

/**
 *
 *
 * @uses      HttpServer
 * @version   2017年08月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpServer
{
    /**
     * @var string 启动入口文件
     */
    private $scriptFile = "";

    /**
     * @var array tcp配置信息
     */
    private $tcp;

    /**
     * @var array http配置信息
     */
    private $http;

    /**
     * @var \Swoole\Http\Server http server
     */
    private $swoft;

    /**
     * @var array swoole启动参数
     */
    private $setting;

    /**
     * @var \Swoole\Server\Port tcp监听器
     */
    private $listen;

    /**
     * httpServer运行状态信息
     *
     * @var array
     */
    private $status = [];

    public function __construct()
    {
        // 记载swoft.ini
        $this->loadSwoftIni();
    }

    /**
     * 加载swoft.ini配置
     */
    protected function loadSwoftIni()
    {
        $setings = parse_ini_file(SETTING_PATH, true);
        if (!isset($setings['tcp'])) {
            throw new \InvalidArgumentException("未配置tcp启动参数，settings=" . json_encode($setings));
        }
        if (!isset($setings['http'])) {
            throw new \InvalidArgumentException("未配置http启动参数，settings=" . json_encode($setings));
        }
        if (!isset($setings['server'])) {
            throw new \InvalidArgumentException("未配置server启动参数，settings=" . json_encode($setings));
        }

        if (!isset($setings['setting'])) {
            throw new \InvalidArgumentException("未配置setting启动参数，settings=" . json_encode($setings));
        }

        $this->tcp = $setings['tcp'];
        $this->http = $setings['http'];
        $this->status = $setings['server'];
        $this->setting = $setings['setting'];
    }

    /**
     * 启动Http Server
     */
    public function start()
    {
        $this->swoft = new \Swoole\Http\Server($this->http['host'], $this->http['port'], $this->http['model'], $this->http['type']);

        $this->swoft->set($this->setting);
        $this->swoft->on('start', [$this, 'onStart']);
        $this->swoft->on('workerstart', [$this, 'onWorkerStart']);
        $this->swoft->on('managerstart', [$this, 'onManagerStart']);
        $this->swoft->on('request', [$this, 'onRequest']);

        if ((int)$this->tcp['enable'] === 1) {
            $this->listen = $this->swoft->listen($this->tcp['host'], $this->tcp['port'], $this->tcp['type']);
            $this->listen->set([
                'open_eof_check'     => false,
                'package_max_length' => 20480,
            ]);
            $this->listen->on('connect', [$this, 'onConnect']);
            $this->listen->on('receive', [$this, 'onReceive']);
            $this->listen->on('close', [$this, 'onClose']);
        }
        $reloadProcess = new \Swoole\Process(function ($process){
            $process->name('php-swf reload process');
            $inotify = new Inotify($this);
            $inotify->run();
        }, false, 2);

        $this->swoft->addProcess($reloadProcess);

        $this->swoft->start();
    }

    /**
     * http请求每次会启动一个协程
     *
     * @param \Swoole\Http\Request  $request
     * @param \Swoole\Http\Response $response
     *
     * @return bool|void
     */
    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        App::getApplication()->doRequest($request, $response);
    }

    /**
     * RPC请求每次启动一个协程来处理
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     * @param int            $fromId
     * @param string         $data
     */
    public function onReceive(\Swoole\Server $server, int $fd, int $fromId, string $data)
    {
        App::getApplication()->doReceive($server, $fd, $fromId, $data);
    }

    public function onConnect(\Swoole\Server $server, int $fd, int $from_id)
    {
        var_dump("connnect------");
    }

    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        var_dump("close------");
    }

    /**
     * master进程启动前初始化
     *
     * @param \Swoole\Http\Server $server
     */
    public function onStart(\Swoole\Http\Server $server)
    {
        file_put_contents($this->status['pfile'], $server->master_pid);
        file_put_contents($this->status['pfile'], ',' . $server->manager_pid, FILE_APPEND);
        swoole_set_process_name($this->status['pname'] . " master process (" . $this->scriptFile . ")");
    }

    /**
     * mananger进程启动前初始化
     *
     * @param \Swoole\Http\Server $server
     */
    public function onManagerStart(\Swoole\Http\Server $server)
    {
        swoole_set_process_name($this->status['pname'] . " manager process");
    }

    /**
     * worker进程启动前初始化
     *
     * @param \Swoole\Http\Server $server
     * @param int                 $workerId
     */
    public function onWorkerStart(\Swoole\Http\Server $server, int $workerId)
    {
        $setting = $server->setting;
        if ($workerId >= $setting['worker_num']) {
            swoole_set_process_name($this->status['pname'] . " task process");
        } else {
            swoole_set_process_name($this->status['pname'] . " worker process");
        }

        // 加载配置
        $definitions = require CONFIG_PATH;

        // 初始化beans
        $beanFactory = new BeanFactory($definitions);

        // 加载路由
        require ROUTES_PATH;
    }


    /**
     * reload服务
     *
     * @param bool $reloadTask
     */
    public function reload($reloadTask = false)
    {
        $onlyTask = $reloadTask ? SIGUSR2 : SIGUSR1;
        posix_kill($this->status['managerPid'], $onlyTask);
    }

    /**
     * stop服务
     */
    public function stop()
    {
        $timeout = 60;
        $startTime = time();
        $this->status['masterPid'] && posix_kill($this->status['masterPid'], SIGTERM);

        $result = true;
        while (1) {
            $masterIslive = $this->status['masterPid'] && posix_kill($this->status['masterPid'], SIGTERM);
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
        $pFile = $this->status['pfile'];

        // pid 文件是否存在
        if (file_exists($pFile)) {
            // 文件内容解析
            $pidFile = file_get_contents($pFile);
            $pids = explode(',', $pidFile);

            $this->status['masterPid'] = $pids[0];
            $this->status['managerPid'] = $pids[1];
            $masterIsLive = $this->status['masterPid'] && @posix_kill($this->status['managerPid'], 0);
        }

        return $masterIsLive;
    }

    /**
     * 设置是否守护进程启动
     *
     * @param int $daemonize
     */
    public function setDaemonize(int $daemonize)
    {
        $this->setting['daemonize'] = $daemonize;
    }

    /**
     * 设置启动脚本文件
     *
     * @param string $scriptFile
     */
    public function setScriptFile(string $scriptFile)
    {
        $this->scriptFile = $scriptFile;
    }

    /**
     * 获取http server
     *
     * @return \Swoole\Http\Server
     */
    public function getServer()
    {
        return $this->swoft;
    }

    /**
     * 获取启动server状态
     *
     * @return array
     */
    public function getServerStatus()
    {
        return $this->status;
    }

    /**
     * 获取tcp启动参数
     *
     * @return array
     */
    public function getTcpStatus()
    {
        return $this->tcp;
    }

    /**
     * 获取http启动参数
     *
     * @return array
     */
    public function getHttpStatus()
    {
        return $this->http;
    }
}
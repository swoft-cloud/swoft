<?php

namespace Swoft\Base;

use Swoft\App;

/**
 * Http服务器
 *
 * @uses      HttpServer
 * @version   2017年08月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpServer
{
    /**
     * @var array tcp配置信息
     */
    protected $tcp;

    /**
     * @var array http配置信息
     */
    protected $http;

    /**
     * @var \Swoole\Http\Server http server
     */
    protected $swoft;

    /**
     * @var array swoole启动参数
     */
    protected $setting;

    /**
     * @var \Swoole\Server\Port tcp监听器
     */
    protected $listen;

    /**
     * @var string 启动入口文件
     */
    protected $scriptFile = "";

    /**
     * httpServer运行状态信息
     *
     * @var array
     */
    protected $status = [];

    /**
     * HttpServer constructor.
     */
    public function __construct()
    {
        // 加载swoft.ini
        $this->loadSwoftConfig();
    }

    /**
     * 加载swoft.ini配置
     */
    protected function loadSwoftConfig()
    {
        $settingsPath = App::getAlias('@settings');
        $setings = parse_ini_file($settingsPath, true);
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

        if(isset($setings['setting']['log_file'])){
            $logPath = $setings['setting']['log_file'];
            $setings['setting']['log_file'] = App::getAlias($logPath);
        }

        $this->tcp = $setings['tcp'];
        $this->http = $setings['http'];
        $this->status = $setings['server'];
        $this->setting = $setings['setting'];

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
     * 获取http server
     *
     * @return \Swoole\Http\Server
     */
    public function getServer()
    {
        return $this->swoft;
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
}
<?php

namespace Swoft\Base;

use Swoft\App;
use Swoft\Crontab\Crontab;
use Swoole\Process;

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
     * @var array crontab配置信息
     */
    protected $crontab;

    /**
     * @var tcp监听器
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

        if (!isset($settings['crontab'])) {
            $settings['crontab']['enable'] = 0;
        } else {
            if ($settings['crontab']['enable'] === 1) {
                !isset($settings['setting']['task_worker_num']) && $settings['setting']['task_worker_num'] = 0;
                $settings['setting']['task_worker_num'] = (int)(abs($settings['setting']['task_worker_num'])) + Crontab::TASKER_NUM;
            }
        }

        $this->tcp = $settings['tcp'];
        $this->http = $settings['http'];
        $this->status = $settings['server'];
        $this->setting = $settings['setting'];
        $this->crontab = $settings['crontab'];
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
        $this->tcp['enable'] = 1;
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
     * 加载Bean
     */
    protected function initLoadBean(): bool
    {
        require_once BASE_PATH . '/config/reload.php';

        return true;
    }

    /**
     * 唤醒crontab
     *
     * @param \Swoole\Server $server
     * @param int            $workerId
     *
     * @return bool
     */
    protected function wakeUpCrontab(\Swoole\Server $server, int $workerId): bool
    {
        if (!$this->crontab['enable']) {
            return false;
        }

        $setting = $server->setting;

        // 第1/2个tasker进程添加定时器
        if (($taskerId = $workerId - $setting['worker_num']) === 0 || ($taskerId = $workerId - $setting['worker_num']) === 1) {
            if (($crontab = App::getCrontab())->getTaskList()) {
                if ($taskerId === 1) {
                    swoole_set_process_name('php-swf : timer-crontab');
                    // 分整点载入
                    $server->after(((60 - date('s')) * 1000), function () use ($server, $crontab) {
                        // 每分钟检查一次,把下一分钟需要执行的任务列出来
                        $crontab->checkTask();
                        $server->tick(60 * 1000, function () use ($crontab) {
                            $crontab->checkTask();
                        });
                    });
                } else {
                    $server->tick(0.5 * 1000, function () use ($crontab) {
                        $tasks = $crontab->getExecTasks();
                        if (!empty($tasks)) {
                            foreach ($tasks as $task) {
                                $process = new Process(function (Process $process) use ($task, $crontab) {
                                    $process->exec($task['cmd'], $task['args']);
                                    $crontab->finishTask($task['key']);
                                }, false, false);
                                $process->name('php-swf : exec-crontab');
                                $pid = $process->start();

                                //回收子进程
                                $ret = \Swoole\Process::wait();
                            }
                        }
                    });
                }
            }
        }

        return true;
    }

    /**
     * 判断task进程是否为crontab进程
     * 如果启用了crontab , 由于tasker进程的第1/2个tasker进程用于crontab
     * 如果投递task任务到第1/2个tasker进程的话，将不处理内容
     *
     * @param \Swoole\Server $server
     * @param int            $workerId
     *
     * @return bool
     */
    protected function isCrontabTask($server, int $workerId): bool
    {
        $taskerId = $workerId - $server->setting['worker_num'];

        if ((int)$this->crontab['enable'] && in_array($taskerId, [0, 1])) {
            return true;
        }

        return false;
    }

    /**
     * Tasker进程回调
     *
     * @param \Swoole\Server $server
     * @param int            $taskId
     * @param int            $workerId
     * @param mixed          $data
     *
     * @return mixed
     *
     */
    public function onTask(\Swoole\Server $server, int $taskId, int $workerId, $data)
    {
        return $data;
    }
}

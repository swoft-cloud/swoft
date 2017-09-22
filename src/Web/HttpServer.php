<?php

namespace Swoft\Web;

use Swoft\App;
use Swoft\Base\Inotify;
use Swoole\Process;

/**
 * http服务器
 *
 * @uses      HttpServer
 * @version   2017年08月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpServer extends \Swoft\Base\HttpServer
{
    /**
     * 启动Http Server
     */
    public function start()
    {
        App::$server = $this;

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

        $this->beforeStart();
        $this->swoft->start();
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

        // reload重新加载文件
        $this->beforeOnWorkerStart();
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

    /**
     * 重新加载reload回调函数
     *
     * @param Process $process
     */
    public function reloadCallback(Process $process)
    {
        $processName = $this->status['pname'] . " reload process";
        $process->name($processName);
        $inotify = new Inotify($this);
        $inotify->run();
    }

    /**
     * master start之前运行
     */
    private function beforeStart()
    {
        if (!AUTO_RELOAD || !extension_loaded('inotify')) {
            echo "自动reload未开启，请检查配置(AUTO_RELOAD)和inotify扩展是否安装正确! \n";
            return;
        }

        // 添加重新加载进程
        $reloadProcess = new Process([$this, 'reloadCallback'], false, 2);
        $this->swoft->addProcess($reloadProcess);
    }

    /**
     * worker start之前运行
     */
    private function beforeOnWorkerStart()
    {
        require_once BASE_PATH . '/config/reload.php';
    }

    public function onConnect(\Swoole\Server $server, int $fd, int $from_id)
    {
        var_dump("connnect------");
    }

    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        var_dump("close------");
    }
}

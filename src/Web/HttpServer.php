<?php

namespace Swoft\Web;

use Swoft\App;
use Swoft\Base\Context;
use Swoft\Base\Inotify;
use Swoft\Event\Event;
use Swoft\Event\Events\BeforeTaskEvent;
use Swoft\Task\Task;
use Swoole\Http\Server;
use Swoole\Lock;
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
     * @var Lock;
     */
    private $workerLock;

    /**
     * 启动Http Server
     */
    public function start()
    {
        App::$server = $this;
        $this->workerLock = new Lock(SWOOLE_RWLOCK);

        $this->swoft = new Server($this->http['host'], $this->http['port'], $this->http['model'], $this->http['type']);

        $this->swoft->set($this->setting);
        $this->swoft->on('start', [$this, 'onStart']);
        $this->swoft->on('workerstart', [$this, 'onWorkerStart']);
        $this->swoft->on('managerstart', [$this, 'onManagerStart']);
        $this->swoft->on('request', [$this, 'onRequest']);
        $this->swoft->on('task', [$this, 'onTask']);
        $this->swoft->on('finish', [$this, 'onFinish']);

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
     * @param Server $server
     */
    public function onStart(Server $server)
    {
        file_put_contents($this->status['pfile'], $server->master_pid);
        file_put_contents($this->status['pfile'], ',' . $server->manager_pid, FILE_APPEND);
        swoole_set_process_name($this->status['pname'] . " master process (" . $this->scriptFile . ")");

    }

    /**
     * mananger进程启动前初始化
     *
     * @param Server $server
     */
    public function onManagerStart(Server $server)
    {
        swoole_set_process_name($this->status['pname'] . " manager process");

        //        $process = new \swoole_process(function (\swoole_process $process) {
        //
        //            swoole_timer_tick(1000, function(){
        //                echo "timeout\n";
        //            });
        //        }, false);
        //
        //        $process->name('php-swf crontab-execute');
        //        $pid = $process->start();
        //        var_dump($pid);
        //
        //        $process2 = new \swoole_process(function (\swoole_process $process) {
        //
        //            swoole_timer_tick(1000, function(){
        //                echo "timeout\n";
        //            });
        //        }, false);
        //
        //        $process2->name('php-swf crontab-scan');
        //        $pid = $process2->start();
        //
        //        var_dump($pid);
        //
        //        $pids = \Swoole\Process::wait();
    }

    /**
     * worker进程启动前初始化
     *
     * @param Server $server
     * @param int    $workerId
     */
    public function onWorkerStart(Server $server, int $workerId)
    {
        // reload重新加载文件
        $this->beforeOnWorkerStart($server, $workerId);

        $setting = $server->setting;
        if ($workerId >= $setting['worker_num']) {
            Context::setStatus(Context::TASK);
            swoole_set_process_name($this->status['pname'] . " task process");
        } else {
            Context::setStatus(Context::WORKER);
            swoole_set_process_name($this->status['pname'] . " worker process");
        }
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
        $this->sharedMemory();

        if (!AUTO_RELOAD || !extension_loaded('inotify')) {
            echo "自动reload未开启，请检查配置(AUTO_RELOAD)和inotify扩展是否安装正确! \n";
            return;
        }

        // 添加重新加载进程
        $reloadProcess = new Process([$this, 'reloadCallback'], false, 2);


        //        $this->process = $process;

        $this->swoft->addProcess($reloadProcess);
        //        $this->swoft->addProcess($process);
    }

    /**
     * master进程加载前
     */
    private function sharedMemory()
    {
        App::setCrontab();
    }

    /**
     * worker start之前运行
     *
     * @param Server $server
     * @param int    $workerId
     */
    private function beforeOnWorkerStart(Server $server, int $workerId)
    {
        // 加载bean
        $this->initLoadBean();

        $this->initProcess();
        // 校验是否启动crontab
        //        $this->wakeUpCrontab($server, $workerId);
    }

    private function initProcess(){
        $isTask = $this->swoft->taskworker;
        if( $isTask === false && $this->workerLock->trylock()){
            Context::setStatus(Context::PROCESS);
            $pname = $this->status['pname'];
            \Swoft\Process\Process::run($pname);
        }
    }

    /**
     * 连接成功后回调函数
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     * @param int            $from_id
     *
     */
    public function onConnect(\Swoole\Server $server, int $fd, int $from_id)
    {
        var_dump("connnect------");
    }

    /**
     * 连接断开成功后回调函数
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     * @param int            $reactorId
     *
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        var_dump("close------");
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
        // 设置taskId
        Task::setId($taskId);

        // 用户自定义的任务，不是字符串
        if(!is_string($data)){
            return parent::onTask($server, $taskId, $workerId, $data);
        }

        // 用户自定义的任务，不是序列化字符串
        $task = @unserialize($data);
        if($task === false){
            return parent::onTask($server, $taskId, $workerId, $data);
        }

        // 用户自定义的任务，不存在类型
        if (!isset($task['type'])) {
            return parent::onTask($server, $taskId, $workerId, $data);
        }

        $name = $task['name'];
        $type = $task['type'];
        $method = $task['method'];
        $params = $task['params'];
        $logid = $task['logid'] ?? uniqid();
        $spanid = $task['spanid'] ?? 0;

        $event = new BeforeTaskEvent($this,$logid, $spanid, $name, $method, $type);
        App::trigger(Event::BEFORE_TASK, $event);
        $result = Task::run($name, $method, $params);
        App::trigger(Event::AFTER_TASK, null, $type);

        if($type == Task::TYPE_CRON){
            return $result;
        }
        $server->finish($result);
    }

    /**
     * worker收到tasker消息的回调函数
     *
     * @param \Swoole\Server $server
     * @param int            $taskId
     * @param mixed          $data
     */
    public function onFinish(\Swoole\Server $server, int $taskId, $data)
    {
        var_dump($data,'----------((((((9999999999');
    }
}

<?php

namespace Swoft\Server;

use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Base\InitApplicationContext;
use Swoft\Bean\BeanFactory;
use Swoft\Event\Event;
use Swoft\Event\Events\BeforeTaskEvent;
use Swoft\Helper\ProcessHelper;
use Swoft\Process\Process;
use Swoft\Task\Task;
use Swoole\Server;
use Swoft\Crontab\TableCrontab;

/**
 * RPC服务器
 *
 * @uses      RpcServer
 * @version   2017年10月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RpcServer extends AbstractServer
{

    /**
     * 启动服务器
     */
    public function start()
    {
        // rpc server
        $this->server = new Server($this->tcpSetting['host'], $this->tcpSetting['port'], $this->tcpSetting['model'], $this->tcpSetting['type']);

        // 设置回调函数
        $listenSetting = $this->getListenTcpSetting();
        $setting = array_merge($this->setting, $listenSetting);
        $this->server->set($setting);
        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('workerstart', [$this, 'onWorkerStart']);
        $this->server->on('managerstart', [$this, 'onManagerStart']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('connect', [$this, 'onConnect']);
        $this->server->on('receive', [$this, 'onReceive']);
        $this->server->on('pipeMessage', [$this, 'onPipeMessage']);
        $this->server->on('close', [$this, 'onClose']);

        // before start
        $this->beforeStart();
        $this->server->start();
    }

    /**
     * master进程启动前初始化
     *
     * @param Server $server
     */
    public function onStart(Server $server)
    {
        file_put_contents($this->serverSetting['pfile'], $server->master_pid);
        file_put_contents($this->serverSetting['pfile'], ',' . $server->manager_pid, FILE_APPEND);
        ProcessHelper::setProcessTitle($this->serverSetting['pname'] . " master process (" . $this->scriptFile . ")");
    }

    /**
     * mananger进程启动前初始化
     *
     * @param Server $server
     */
    public function onManagerStart(Server $server)
    {
        ProcessHelper::setProcessTitle($this->serverSetting['pname'] . " manager process");
    }

    /**
     * worker进程启动前初始化
     *
     * @param Server $server   server
     * @param int    $workerId workerId
     */
    public function onWorkerStart(Server $server, int $workerId)
    {
        // worker和task进程初始化
        $setting = $server->setting;
        if ($workerId >= $setting['worker_num']) {
            ApplicationContext::setContext(ApplicationContext::TASK);
            ProcessHelper::setProcessTitle($this->serverSetting['pname'] . " task process");
        } else {
            ApplicationContext::setContext(ApplicationContext::WORKER);
            ProcessHelper::setProcessTitle($this->serverSetting['pname'] . " worker process");
        }

        // reload重新加载文件
        $this->beforeOnWorkerStart($server, $workerId);
    }

    /**
     * RPC请求每次启动一个协程来处理
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $fromId
     * @param string $data
     */
    public function onReceive(Server $server, int $fd, int $fromId, string $data)
    {
        App::getApplication()->doReceive($server, $fd, $fromId, $data);
    }

    /**
     * 管道消息处理
     *
     * @param Server $server
     * @param int    $fromWorkerId
     * @param string $message
     */
    public function onPipeMessage(Server $server, int $fromWorkerId, string $message)
    {
        list($type, $data) = PipeMessage::unpack($message);
        if ($type == PipeMessage::TYPE_TASK) {
            $this->onPipeMessageTask($data);
        }
    }


    /**
     * 连接成功后回调函数
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $from_id
     *
     */
    public function onConnect(Server $server, int $fd, int $from_id)
    {
        var_dump("connnect------");
    }

    /**
     * 连接断开成功后回调函数
     *
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     */
    public function onClose(Server $server, int $fd, int $reactorId)
    {
        var_dump("close------");
    }

    /**
     * Tasker进程回调
     *
     * @param Server $server
     * @param int    $taskId
     * @param int    $workerId
     * @param mixed  $data
     *
     * @return mixed
     *
     */
    public function onTask(Server $server, int $taskId, int $workerId, $data)
    {
        // 设置taskId
        Task::setId($taskId);

        // 用户自定义的任务，不是字符串
        if (!is_string($data)) {
            return parent::onTask($server, $taskId, $workerId, $data);
        }

        // 用户自定义的任务，不是序列化字符串
        $task = @unserialize($data);
        if ($task === false) {
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

        $event = new BeforeTaskEvent($this, $logid, $spanid, $name, $method, $type);
        App::trigger(Event::BEFORE_TASK, $event);
        $result = Task::run($name, $method, $params);
        App::trigger(Event::AFTER_TASK, null, $type);

        if ($type == Task::TYPE_CRON) {
            return $result;
        }
        $server->finish($result);
    }

    /**
     * worker收到tasker消息的回调函数
     *
     * @param Server $server
     * @param int    $taskId
     * @param mixed  $data
     */
    public function onFinish(Server $server, int $taskId, $data)
    {
        //        var_dump($data, '----------((((((9999999999');
    }

    /**
     * @param string $scriptFile
     */
    public function setScriptFile(string $scriptFile)
    {
        $this->scriptFile = $scriptFile;
    }

    /**
     * swoole server start之前运行
     */
    protected function beforeStart()
    {
        // 添加共享内存表
        $this->addShareMemory();
        // 添加用户自定义进程
        $this->addUserProcesses();
    }

    /**
     * 添加共享内存表
     */
    private function addShareMemory()
    {
        // 初始化定时任务共享内存表
        if (isset($this->serverSetting['cronable']) && (int)$this->serverSetting['cronable'] === 1) {
            $this->initCrontabMemoryTable();
        }
    }

    /**
     * 初始化crontab共享内存表
     */
    private function initCrontabMemoryTable()
    {
        $taskCount = isset($this->crontabSetting['task_count']) && $this->crontabSetting['task_count'] > 0 ? $this->crontabSetting['task_count']
            : null;
        $taskQueue = isset($this->crontabSetting['task_queue']) && $this->crontabSetting['task_queue'] > 0 ? $this->crontabSetting['task_queue']
            : null;

        TableCrontab::init($taskCount, $taskQueue);
    }

    /**
     * 任务类型的管道消息
     *
     * @param array $data 数据
     */
    private function onPipeMessageTask(array $data)
    {
        // 任务信息
        $type = $data['type'];
        $taskName = $data['name'];
        $params = $data['params'];
        $timeout = $data['timeout'];
        $methodName = $data['method'];

        // 投递任务
        Task::deliver($taskName, $methodName, $params, $type, $timeout);
    }

    /**
     * 添加自定义进程
     */
    private function addUserProcesses()
    {
        foreach ($this->processSetting as $name => $processClassName) {
            $userProcess = Process::create($this, $name, $processClassName);
            if ($userProcess === null) {
                continue;
            }
            $this->server->addProcess($userProcess);
        }
    }

    /**
     * worker start之前运行
     *
     * @param Server $server   server
     * @param int    $workerId workerId
     */
    private function beforeOnWorkerStart(Server $server, int $workerId)
    {
        // 加载bean
        $this->reloadBean();
    }

    /**
     * reload bean
     */
    protected function reloadBean()
    {
        BeanFactory::reload();
        $initApplicationContext = new InitApplicationContext();
        $initApplicationContext->init();
    }
}

<?php

namespace swoft\base;

use inhere\console\io\Input;
use inhere\console\utils\Show;
use swoft\App;
use swoft\web\InnerService;
use swoft\web\Router;

/**
 * 应用基类
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Application
{
    /**
     * @var array
     */
    protected $status = [];

    private $command;

    /**
     * @var array
     */
    protected $server = [];

    /**
     * @var string 应用ID
     */
    protected $id;

    /**
     * @var string 应用名称
     */
    protected $name;

    /**
     * @var string 应用根目录
     */
    protected $basePath;

    /**
     * @var string 视图目录
     */
    protected $viewsPath;

    /**
     * @var string 运行日志目录
     */
    protected $runtimePath;

    /**
     * @var string http或tcp服务启动配置参数目录
     */
    protected $settingPath;

    /**
     * @var bool 是否使用第三方(consul/etcd/zk)注册服务
     */
    protected $useProvider = false;

    /**
     * @var string 控制器命令空间
     */
    protected $controllerNamespace = "app\\controllers";

    /**
     * @var string 内部服务命令空间
     */
    protected $serviceNameSpace = "app\\controllers\\services";

    public $count = 0;

    /**
     * 启动服务
     */
    public function run()
    {
        $this->parseCommand();
    }

    public function parseCommand()
    {
        $input = new Input;
        $command = $input->getCommand();

        if (!$command || $command === 'help' || $input->getSameOpt(['h', 'help'])) {
            $this->showHelp($input);
        }

        // $this->loadSwoftIni();

        $this->status['startFile'] = $input->getScript();
        $allowCommands = ['start', 'stop', 'reload', 'restart', 'help'];

        if (!in_array($command, $allowCommands, true)) {
            Show::error("The command: $command is not exists.");
            $this->showHelp($input);
        }

        $this->command = $command;
        $this->$command();
    }

    abstract public function start();

    /**
     * stop the swoole application server
     */
    public function stop()
    {
        if (!$this->isRunning()) {
            echo "The server is not running! cannot stop\n";
            exit(0);
        }

        $pidFile = $this->server['pfile'];
        $startFile = $this->status['startFile'];
        @unlink($pidFile);
        echo("swoft $startFile is stopping ... \n");

        $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);

        $timeout = 5;
        $startTime = time();

        while (1) {
            $masterIslive = $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);

            if ($masterIslive) {
                if (time() - $startTime >= $timeout) {
                    echo('swoft ' . $startFile . " stop fail \n");
                    exit;
                }
                usleep(10000);
                continue;
            }

            echo("swoft $startFile stop success \n");
            break;
        }
    }

    /**
     * reload the swoole application server
     * @param bool $onlyTask
     */
    public function reload($onlyTask = false)
    {
        if (!$this->isRunning()) {
            echo "The server is not running! cannot reload\n";
            exit(0);
        }

        $startFile = $this->status['startFile'];

        echo "Server $startFile is reloading \n";

        posix_kill($this->server['managerPid'], $onlyTask ? SIGUSR2 : SIGUSR1);

        echo "Server $startFile reload success \n";
    }

    /**
     * restart the swoole application server
     */
    public function restart()
    {
        if ($this->isRunning()) {
            $this->stop();
        }

        $this->start();
    }

    /**
     * check Status
     * @return bool
     */
    protected function isRunning()
    {
        $masterIsLive = false;
        $pFile = $this->server['pfile'];

        if (file_exists($pFile)) {
            $pidFile = file_get_contents($pFile);
            $pids = explode(',', $pidFile);

            $this->server['masterPid'] = $pids[0];
            $this->server['managerPid'] = $pids[1];
            $masterIsLive = $this->server['masterPid'] && @posix_kill($this->server['managerPid'], 0);
        }

        return $masterIsLive;
    }


    protected function showHelp(Input $input)
    {
        $script = $input->getScriptName();

        Show::helpPanel([
            Show::HELP_DES => 'the application server powered by swoole',
            Show::HELP_USAGE => "$script <cyan>{start|stop|reload|restart}</cyan> [--opt ...]",
            Show::HELP_COMMANDS => [
                'start' => 'start the swoole application server',
                'restart' => 'restart the swoole application server',
                'reload' => 'reload the swoole application server',
                'stop' => 'stop the swoole application server',
                'help' => 'display the help information',
            ],
            Show::HELP_OPTIONS => [
                '-h,--help' => 'display the help information',
                '--only-task' => 'only reload task worker when exec reload command'
            ]
        ]);
    }

    /**
     * 创建控制器
     *
     * @param string $path  url路径
     * @param array  $info  url参数
     * @return array
     * <pre>
     *  [$controller, $action, $matches]
     * </pre>
     * @throws \InvalidArgumentException
     */
    public function createController(string $path, array $info)
    {
        $handler = $info['handler'];
        $matches = $info['matches'] ?? [];

        // Remove $matches[0] as [1] is the first parameter.
        if ($matches) {
            array_shift($matches);
            $matches = array_values($matches);
        }

        // is a \Closure or a callable object
        if (is_object($handler)) {
            return $matches ? $handler(...$matches) : $handler();
        }

        //// $handler is string

        // is array ['controller', 'action']
        if (is_array($handler)) {
            $segments = $handler;
        } elseif (is_string($handler)) {
            // e.g `controllers\Home@index` Or only `controllers\Home`
            $segments = explode('@', trim($handler));
        } else {
            App::error('Invalid route handler for URI: ' . $path);
            throw new \InvalidArgumentException('Invalid route handler for URI: ' . $path);
        }

        $action = '';
        $className = $segments[0];

        // Already assign action
        if (isset($segments[1])) {
            $action = $segments[1];

            // use dynamic action
        } elseif (isset($matches[0])) {
            $action = array_shift($matches);
        }

        $action = Router::convertNodeStr($action);
        $controller = ApplicationContext::getBean($className);

        return [$controller, $action, $matches];
    }

    /**
     * 调用内部服务
     *
     * @param array $data 调用参数信息
     * @return array
     */
    public function runService(array $data)
    {
        $func = $data['func']?? "";
        $params = $data['params']?? [];

        list($servicePrefix, $method) = explode("::", $func);

        $namespace = $this->serviceNameSpace;
        $class = $servicePrefix . "Service";
        $className = $namespace . "\\" . $class;
        if (!class_exists($className)) {
            App::error("内部服务调用的class不存在,class=".$className);
            throw new \InvalidArgumentException("内部服务调用的class不存在,class=".$className);
        }

        if ($className instanceof InnerService) {
            App::error("内部服务调用的class不是InnerService子类,class=".$className);
            throw new \InvalidArgumentException("内部服务调用的class不是InnerService子类,class=".$className);
        }

        if (empty($method)) {
            App::error("内部服务调用的class不是InnerService子类,class=".$className);
            throw new \InvalidArgumentException("内部服务调用的method为空,method=".$method);
        }

        /* @var $service InnerService */
        $service = App::getBean($className);
        $data = $service->run($method, $params);

        return $data;
    }
}

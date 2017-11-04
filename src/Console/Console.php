<?php

namespace Swoft\Console;

use Swoft\App;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Console\Style\Style;
use Swoft\Helper\PhpHelper;
use Swoft\Web\ErrorHandler;

/**
 * 命令行
 *
 * @uses      Application
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Console implements IConsole
{
    /**
     * 默认命令组
     */
    const DEFAULT_CMD_GROUP = 'server';

    /**
     * 默认命令
     */
    const DEFAULT_CMD = "index";

    /**
     * 命令分隔符
     */
    const DELIMITER = ':';

    /**
     * 命令后缀
     */
    const CONTROLLER_SUFFIX = 'Controller';

    /**
     * 默认命令
     */
    const DEFAULT_CMDS
        = [
            'start',
            'reload',
            'stop',
            'restart'
        ];

    /**
     * 参数输入
     *
     * @var Input
     */
    private $input;

    /**
     * 参数输出
     *
     * @var Output
     */
    private $output;

    /**
     * 命令扫描目录
     *
     * @var array
     */
    private $scanCmds = [];

    /**
     * 错误处理器
     *
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * 每个命令唯一ID
     *
     * @var int
     */
    private static $id;

    /**
     * 初始化
     */
    public function __construct()
    {
        // 初始化样式
        Style::init();

        // 初始化
        self::$id = time();
        $this->registerNamespace();
        $this->input = new Input();
        $this->output = new Output();
        $this->errorHandler = new ErrorHandler();
        $this->errorHandler->register();
    }

    /**
     * 运行命令行
     */
    public function run()
    {
        // 默认命令解析
        $cmd = $this->input->getCommand();
        if (in_array($cmd, self::DEFAULT_CMDS, true)) {
            $cmd = sprintf("%s:%s", self::DEFAULT_CMD_GROUP, $cmd);
        }

        // 没有任何命令输入
        if (empty($cmd)) {
            $this->baseCommand();
            return;
        }

        // 运行命令
        try {
            $this->dispather($cmd);
        } catch (\Throwable $e) {
            $this->output->writeln(sprintf('<error>%s</error>', $e->getMessage()), true, true);
        }
    }

    /**
     * 引导命令界面
     */
    private function baseCommand()
    {
        // 版本命令解析
        if ($this->input->hasOpt('v') || $this->input->hasOpt('version')) {
            $this->showVersion();
            return;
        }

        // 显示命令列表
        $this->showCommandList();
    }

    /**
     * 显示命令列表
     */
    private function showCommandList()
    {
        // 命令目录扫描
        $commands = [];
        foreach ($this->scanCmds as $namespace => $dir) {
            $iterator = new \RecursiveDirectoryIterator($dir);
            $files = new \RecursiveIteratorIterator($iterator);

            $scanCommands = $this->parserCmdAndDesc($namespace, $files);
            $commands = array_merge($commands, $scanCommands);
        }

        // 组拼命令结构
        $commandList = [];
        $script = $this->input->getFullScript();
        $commandList['Usage:'] = ["php $script"];
        $commandList['Commands:'] = $commands;
        $commandList['Options:'] = [
            '-v,--version' => 'show version',
            '-h,--help'    => 'show help'
        ];

        // 显示Logo
        $this->output->writeLogo();

        // 显示命令组列表
        $this->output->writeList($commandList, 'comment', 'info');
    }

    /**
     * 解析命令和命令描述
     *
     * @param string         $namespace 命名空间
     * @param \SplFileInfo[] $files     文件集合
     *
     * @return array
     */
    private function parserCmdAndDesc($namespace, $files)
    {
        $commands = [];
        foreach ($files as $file) {

            // 排除非PHP文件
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext != 'php') {
                continue;
            }

            // 命令类名
            $fileName = $file->getFilename();
            list($class) = explode('.', $fileName);
            $className = $namespace . '\\' . $class;

            // 反射获取命令描述
            $rc = new \ReflectionClass($className);
            $docComment = $rc->getDocComment();
            $docAry = DocumentParser::tagList($docComment);
            $desc = $docAry['description'];

            // 解析出命令
            $cmdName = str_replace(self::CONTROLLER_SUFFIX, '', $class);
            $cmd = strtolower($cmdName);

            $commands[$cmd] = $desc;
        }

        return $commands;
    }

    /**
     * 显示版本信息
     */
    private function showVersion()
    {
        // 当前版本信息
        $swoftVersion = App::version();
        $phpVersio = phpversion();
        $swooleVersion = swoole_version();

        // 显示面板
        $this->output->writeLogo();
        $this->output->writeln("swoft: <info>$swoftVersion</info>, php: <info>$phpVersio</info>, swoole: <info>$swooleVersion</info>", true);
        $this->output->writeln("");
    }

    /**
     * 运行命令
     *
     * @param string $cmd
     */
    private function dispather(string $cmd)
    {
        // 默认命令处理
        if (strpos($cmd, self::DELIMITER) === false) {
            $cmd = $cmd . self::DELIMITER . self::DEFAULT_CMD;
        }

        // 类和命令
        list($controllerName, $command) = explode(self::DELIMITER, $cmd);

        // 命令匹配
        $isMatch = false;
        $namespaces = array_keys($this->scanCmds);
        foreach ($namespaces as $namespace) {
            $controllerClass = $namespace . "\\" . ucfirst($controllerName) . self::CONTROLLER_SUFFIX;

            // 类不存在
            if (!class_exists($controllerClass)) {
                continue;
            }

            // 选择第一个匹配的类
            $isMatch = true;
            $cmdController = new $controllerClass($this->input, $this->output);
            PhpHelper::call([$cmdController, 'run'], [$command]);
            break;
        }

        // 未匹配到命令
        if ($isMatch == false) {
            $this->output->writeln('<error>命令不存在</error>', true, true);
        }
    }

    /**
     * 添加命令扫描路径
     *
     * @param string $namespace 命令空间
     * @param string $path      路径
     */
    public function addScanNs(string $namespace, string $path)
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException("$path 路径不存在");
        }
        $this->scanCmds[$namespace] = $path;
    }

    /**
     * 返回命名ID
     *
     * @return int
     */
    public static function id()
    {
        return self::$id;
    }

    /**
     * 扫描命名空间注入
     */
    private function registerNamespace()
    {
        $this->scanCmds['Swoft\Console\Command'] = dirname(__FILE__) . "/Command";
        $this->scanCmds[COMMAND_NS] = App::getAlias("@commands");
    }
}

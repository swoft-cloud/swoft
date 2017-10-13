<?php

namespace Swoft\Console;

use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Console\Style\Style;
use Swoft\Helper\PhpHelper;

/**
 *
 *
 * @uses      Application
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Console implements IApplication
{
    const DEFAULT_CMD = 'server';
    const DELIMITER = ':';

    const CONTROLLER_SUFFIX = 'Controller';
    const ABBREVIATE_CMDS
        = [
            'start',
            'reload',
            'stop',
            'restart'
        ];

    /**
     * @var Input
     */
    private $input;

    /**
     * @var Output
     */
    private $output;

    private $scanCmds = [];

    public function __construct()
    {
        Style::init();
        $this->input = new Input();
        $this->output = new Output();
        $this->init();
    }

    public function run()
    {
        $cmd = $this->input->getCommand();
        if (in_array($cmd, self::ABBREVIATE_CMDS)) {
            $cmd = 'server:' . $cmd;
        }

        if (empty($cmd)) {
            $this->baseCommand();
        }

        $this->dispather($cmd);
    }

    private function baseCommand()
    {
        if ($this->input->hasOpt('v') || $this->input->hasOpt('version')) {
            $this->showVersion();
        }

        $this->showCommandList();
    }

    private function showCommandList()
    {
        $itemList = [];
        $commands = [];
        foreach ($this->scanCmds as $namespace => $dir) {
            $iterator = new \RecursiveDirectoryIterator($dir);
            $files = new \RecursiveIteratorIterator($iterator);

            $scanCommands = $this->parserCmdAndDesc($namespace, $files);
            $commands = array_merge($commands, $scanCommands);
        }

        $script = $this->input->getFullScript();
        $itemList['Usage:'] = [$script];
        $itemList['Commands:'] = $commands;
        $itemList['Options:'] = [
            '-v,--version' => '版本信息',
            '-h,--help'    => '帮助信息'
        ];

        $this->output->writeList($itemList, 'comment', 'info');
    }

    /**
     *
     * @param string         $namespace
     * @param \SplFileInfo[] $files
     *
     * @return array
     */
    private function parserCmdAndDesc($namespace, $files)
    {
        $commands = [];

        foreach ($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext != 'php') {
                continue;
            }

            $fileName = $file->getFilename();
            list($class) = explode('.', $fileName);
            $className = $namespace . '\\' . $class;

            $rc = new \ReflectionClass($className);
            $docComment = $rc->getDocComment();
            $docAry = DocumentParser::tagList($docComment);
            $desc = $docAry['description'];
            $cmdName = str_replace(self::CONTROLLER_SUFFIX, '', $class);
            $cmd = strtolower($cmdName);

            $commands[$cmd] = $desc;
        }

        return $commands;
    }

    private function showVersion()
    {
        $this->output->writeln('<info>swoft 1.0 beta</info>', true);
    }

    private function dispather(string $cmd)
    {
        if (strpos($cmd, self::DELIMITER) === false) {
            $cmd = $cmd . self::DELIMITER . "index";
        }

        list($controllerName, $command) = explode(self::DELIMITER, $cmd);

        $namespaces = array_keys($this->scanCmds);
        foreach ($namespaces as $namespace) {
            $controllerClass = $namespace . "\\" . ucfirst($controllerName) . self::CONTROLLER_SUFFIX;

            if (!class_exists($controllerClass)) {
                continue;
            }

            $cmdController = new $controllerClass($this->input, $this->output);

            PhpHelper::call([$cmdController, 'runCommand'], [$command]);

            break;
        }
    }

    private function init()
    {
        $this->scanCmds['Swoft\Console\Command'] = dirname(__FILE__) . "/Command";
    }
}
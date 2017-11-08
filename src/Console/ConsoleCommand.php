<?php

namespace Swoft\Console;

use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Helper\PhpHelper;

/**
 * 命令父类
 *
 * @uses      ConsoleCommand
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ConsoleCommand
{
    /**
     * 命令后缀
     */
    const COMMAND_SUFFIX = 'Command';

    /**
     * 输入
     *
     * @var Input
     */
    protected $input;

    /**
     * 输出
     *
     * @var Output
     */
    protected $output;

    /**
     * 初始化
     *
     * @param Input  $input  输入
     * @param Output $output 输出
     */
    public function __construct(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * display all commands of the group
     */
    public function indexCommand()
    {
        // 解析当前执行命令描述
        $reflectionClass = new \ReflectionClass($this);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $classDocument = $reflectionClass->getDocComment();
        $classDocAry = DocumentParser::tagList($classDocument);
        $classDesc = $classDocAry['description'];

        $methodCommands = [];
        foreach ($methods as $method) {
            $methodName = $method->getName();
            // 排除非命令后缀方法
            if (strpos($methodName, self::COMMAND_SUFFIX) === false) {
                continue;
            }

            // 命令方法描述
            $methodDocument = $method->getDocComment();
            $methodDocAry = DocumentParser::tagList($methodDocument);
            $command = str_replace(self::COMMAND_SUFFIX, '', $methodName);
            $methodCommands[$command] = $methodDocAry['description'];
        }

        // 命令显示结构
        $commandList = [
            'Description:' => [$classDesc],
            'Usage:'       => ['server:{command} [arguments] [options]'],
            'Commands:'    => $methodCommands,
            'Options:'     => [
                '-h,--help' => 'Show help of the command group or specified command action'
            ]
        ];
        $this->output->writeList($commandList);
    }

    /**
     * 执行命令
     *
     * @param string $command 命令行执行的命令
     */
    public function run(string $command)
    {
        // 命令方法不存在
        $commandMethod = ucfirst($command) . self::COMMAND_SUFFIX;
        if (!method_exists($this, $commandMethod)) {
            $this->output->writeln('<error>命令不存在</error>', true, true);
        }

        // help参数处理
        $isHelp = $this->input->hasOpt('h') || $this->input->hasOpt('help');
        if ($isHelp && $command != Console::DEFAULT_CMD) {
            $this->showCommandHelp(static::class, $commandMethod);
            return;
        }

        // 命令不存在处理
        if (!method_exists($this, $commandMethod)) {
            $this->output->writeln('<error>命令不存在</error>', true, true);
        }

        // 前置逻辑
        $this->beforeRun($command);

        // 执行命令
        PhpHelper::call([$this, $commandMethod]);

        // 后置逻辑
        $this->afterRun($command);
    }

    /**
     * 显示命令组具体某一个命令帮助信息
     *
     * @param string $controllerClass 类名
     * @param string $commandMethod   命令方法
     */
    private function showCommandHelp(string $controllerClass, string $commandMethod)
    {
        // 反射获取方法描述
        $reflectionClass = new \ReflectionClass($controllerClass);
        $reflectionMethod = $reflectionClass->getMethod($commandMethod);
        $document = $reflectionMethod->getDocComment();
        $docs = DocumentParser::tagList($document);

        $commondList = [];
        // 描述
        if (isset($docs['description'])) {
            $commondList['Description:'] = explode("\n", $docs['description']);
        }

        // 使用
        if (isset($docs['usage'])) {
            $commondList['Usage:'] = explode("\n", $docs['usage']);
        }

        // 参数
        if (isset($docs['arguments'])) {
            $arguments = $this->parserKeyAndDesc($docs['arguments']);
            $commondList['Arguments:'] = $arguments;
        }

        // 选项
        if (isset($docs['options'])) {
            $options = $this->parserKeyAndDesc($docs['options']);
            $commondList['Options:'] = $options;
        }

        /**
         * 实例
         */
        if (isset($docs['example'])) {
            $commondList['Example:'] = [$docs['example']];
        }

        $this->output->writeList($commondList);
    }

    /**
     * 命令执行前逻辑
     *
     * @param string $command 当前执行命令
     */
    protected function beforeRun(string $command)
    {

    }

    /**
     * 后置执行逻辑
     *
     * @param string $command
     */
    protected function afterRun(string $command){

    }

    /**
     * 解析命令key和描述
     *
     * @param string $document 注解文档
     *
     * @return array
     */
    private function parserKeyAndDesc(string $document)
    {
        $keyAndDesc = [];
        $items = explode("\n", $document);
        foreach ($items as $item) {
            $pos = strpos($item, ' ');
            $key = substr($item, 0, $pos);
            $desc = substr($item, $pos + 1);
            $keyAndDesc[$key] = $desc;
        }
        return $keyAndDesc;
    }
}
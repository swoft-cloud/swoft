<?php

namespace Swoft\Console;

use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Helper\PhpHelper;

/**
 *
 *
 * @uses      ConsoleCommand
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ConsoleCommand
{
    const COMMAND_SUFFIX = 'Command';

    /**
     * @var Input
     */
    protected $input;

    /**
     * @var Output
     */
    protected $output;

    public function __construct(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function runCommand(string $command)
    {
        $commandMethod = ucfirst($command) . self::COMMAND_SUFFIX;

        if (!method_exists($this, $commandMethod)) {
            $this->output->writeln('<error>命令不存在</error>', true, true);
        }

        if ($this->input->hasOpt('h') || $this->input->hasOpt('help')) {
            $this->showCommandHelp($this, $commandMethod);
            return;
        }

        PhpHelper::call([$this, $commandMethod]);
    }

    private function showCommandHelp($controllerClass, $commandMethod)
    {
        $reflectionClass = new \ReflectionClass($controllerClass);
        $reflectionMethod = $reflectionClass->getMethod($commandMethod);
        $document = $reflectionMethod->getDocComment();
        $docs = DocumentParser::tagList($document);

        $itemList = [];
        if (isset($docs['description'])) {
            $itemList['Description:'] = explode("\n", $docs['description']);
        }

        if (isset($docs['usage'])) {
            $itemList['Usage:'] = explode("\n", $docs['usage']);
        }

        if (isset($docs['arguments'])) {

            $arguments = $this->parserKeyAndDesc($docs['arguments']);
            $itemList['Arguments:'] = $arguments;
        }

        if (isset($docs['options'])) {

            $options = $this->parserKeyAndDesc($docs['options']);
            $itemList['Options:'] = $options;
        }

        if (isset($docs['example'])) {
            $itemList['Example:'] = [$docs['example']];
        }

        $this->output->writeList($itemList);
    }

    private function parserKeyAndDesc(string $document)
    {
        $keyAndDesc = [];
        $items = explode("\n", $document);
        foreach ($items as $item) {
            list($key, $desc) = explode(' ', $item);
            $keyAndDesc[$key] = $desc;
        }
        return $keyAndDesc;
    }
}
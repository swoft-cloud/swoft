<?php

namespace Swoft\Console\Input;

use Swoft\Console\CommandParser;

/**
 * 参数输入
 *
 * @uses      Input
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Input implements IInput
{
    /**
     * 资源句柄
     *
     * @var resource
     */
    protected $handle = STDIN;

    /**
     * 当前目录
     *
     * @var
     */
    private $pwd;

    /**
     * 完整脚本
     *
     * @var string
     */
    private $fullScript;

    /**
     * 脚本
     *
     * @var string
     */
    private $script;

    /**
     * 执行的命令
     *
     * @var string
     */
    private $command;

    /**
     * 输入参数集合
     *
     * @var array
     */
    private $args = [];

    /**
     * 短参数
     *
     * @var array
     */
    private $sOpts = [];

    /**
     * 长参数
     *
     * @var array
     */
    private $lOpts = [];

    /**
     * 初始化
     *
     * @param null|array $argv
     */
    public function __construct($argv = null)
    {
        // 命令输入信息
        if (null === $argv) {
            $argv = $_SERVER['argv'];
        }

        // 初始化
        $this->pwd = $this->getPwd();
        $this->fullScript = implode(' ', $argv);
        $this->script = array_shift($argv);

        // 解析参数和选项
        list($this->args, $this->sOpts, $this->lOpts) = CommandParser::parse($argv);
        $this->command = isset($this->args[0]) ? array_shift($this->args) : null;
    }

    /**
     * 读取用户的输入信息
     *
     * @param null $question 信息
     * @param bool $nl       是否换行
     *
     * @return string
     */
    public function read($question = null, $nl = false): string
    {
        fwrite($this->handle, $question . ($nl ? "\n" : ''));
        return trim(fgets($this->handle));
    }

    /**
     * 所有参数
     *
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * 是否存在某个参数
     *
     * @param string $name 参数名称
     *
     * @return bool
     */
    public function hasArg(string $name): bool
    {
        return isset($this->args[$name]);
    }

    /**
     * 获取某个参数
     *
     * @param int|null|string $name    参数名称
     * @param null            $default 默认值
     *
     * @return mixed|null
     */
    public function getArg($name, $default = null)
    {
        return $this->get($name, $default);
    }

    /**
     * 获取必要参数
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getRequiredArg(string $name)
    {
        if ('' !== $this->get($name, '')) {
            return $this->args[$name];
        }

        throw new \InvalidArgumentException("The argument '{$name}' is required");
    }

    /**
     * 获取相同的参数功能值
     *
     * @param array $names   不同的参数名称
     * @param null  $default 默认值
     *
     * @return mixed|null
     */
    public function getSameArg(array $names, $default = null)
    {
        return $this->sameArg($names, $default);
    }

    /**
     * 获取相同参数的值
     *
     * @param array $names   不同的参数名称
     * @param null  $default 默认值
     *
     * @return mixed|null
     */
    public function sameArg(array $names, $default = null)
    {
        foreach ($names as $name) {
            if ($this->hasArg($name)) {
                return $this->get($name);
            }
        }

        return $default;
    }

    /**
     * 获取选项
     *
     * @param string $name    名称
     * @param null   $default 默认值
     *
     * @return mixed|null
     */
    public function getOpt(string $name, $default = null)
    {
        if (isset($name{1})) {
            return $this->getLongOpt($name, $default);
        }

        return $this->getShortOpt($name, $default);
    }

    /**
     * 获取必须选项
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function getRequiredOpt(string $name)
    {
        $val = $this->getOpt($name);
        if ($val === null) {
            throw new \InvalidArgumentException("The option '{$name}' is required");
        }

        return $val;
    }

    /**
     * 是否存在某个选项
     *
     * @param string $name 名称
     *
     * @return bool
     */
    public function hasOpt(string $name): bool
    {
        return isset($this->sOpts[$name]) || isset($this->lOpts[$name]);
    }

    /**
     * 获取相同的选项
     *
     * @param array $names   不同选项名称
     * @param mixed $default 默认值
     *
     * @return bool|mixed|null
     */
    public function getSameOpt(array $names, $default = null)
    {
        return $this->sameOpt($names, $default);
    }

    /**
     * 获取相同的选项
     *
     * @param array $names   不同选项名称
     * @param mixed $default 默认值
     *
     * @return bool|mixed|null
     */
    public function sameOpt(array $names, $default = null)
    {
        foreach ($names as $name) {
            if ($this->hasOpt($name)) {
                return $this->getOpt($name);
            }
        }

        return $default;
    }

    /**
     * 获取短选项
     *
     * @param string $name    名称
     * @param null   $default 默认值
     *
     * @return mixed|null
     */
    public function getShortOpt(string $name, $default = null)
    {
        return $this->sOpts[$name] ?? $default;
    }

    /**
     * 是否存在某个短选项
     *
     * @param string $name 名称
     *
     * @return bool
     */
    public function hasSOpt(string $name): bool
    {
        return isset($this->sOpts[$name]);
    }

    /**
     * 所有短选项
     *
     * @return array
     */
    public function getShortOpts(): array
    {
        return $this->sOpts;
    }

    /**
     * 所有短选项
     *
     * @return array
     */
    public function getSOpts(): array
    {
        return $this->sOpts;
    }

    /**
     * 获取某个长选项
     *
     * @param string $name    名称
     * @param null   $default 默认值
     *
     * @return mixed|null
     */
    public function getLongOpt(string $name, $default = null)
    {
        return $this->lOpts[$name] ?? $default;
    }

    /**
     * 是否存在某个长选项
     *
     * @param string $name 名称
     *
     * @return bool
     */
    public function hasLOpt(string $name): bool
    {
        return isset($this->lOpts[$name]);
    }

    /**
     * 所有长选项
     *
     * @return array
     */
    public function getLongOpts(): array
    {
        return $this->lOpts;
    }

    /**
     * 所有长选项
     *
     * @return array
     */
    public function getLOpts(): array
    {
        return $this->lOpts;
    }

    /**
     * 所有长和短选项
     *
     * @return array
     */
    public function getOpts(): array
    {
        return array_merge($this->sOpts, $this->lOpts);
    }

    /**
     * 全脚本
     *
     * @return string
     */
    public function getFullScript(): string
    {
        return $this->fullScript;
    }

    /**
     * 脚本
     *
     * @return string
     */
    public function getScript(): string
    {
        return $this->script;
    }

    /**
     * 当前执行的命令
     *
     * @param string $default
     *
     * @return string
     */
    public function getCommand($default = ''): string
    {
        return $this->command ?: $default;
    }

    /**
     * 当前执行目录
     *
     * @return string
     */
    public function getPwd(): string
    {
        if (!$this->pwd) {
            $this->pwd = getcwd();
        }

        return $this->pwd;
    }

    /**
     * 获取某个参数值
     *
     * @param string $name    名称
     * @param null   $default 默认值
     *
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        return $this->args[$name] ?? $default;
    }
}
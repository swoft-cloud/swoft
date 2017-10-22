<?php

namespace Swoft\Console\Input;

/**
 * 输入接口
 *
 * @uses      IInput
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IInput
{
    /**
     * 读取输入信息
     *
     * @param  string $question 若不为空，则先输出文本消息
     * @param  bool   $nl       true 会添加换行符 false 原样输出，不添加换行符
     *
     * @return string
     */
    public function read($question = null, $nl = false): string;

    /**
     * 获取执行的脚本
     *
     * @return string
     */
    public function getScript(): string;

    /**
     * 获取执行的命令
     *
     * @param string $default
     *
     * @return string
     */
    public function getCommand($default = ''): string;

    /**
     * 获取输入参数集合
     *
     * @return array
     */
    public function getArgs(): array;

    /**
     * 获取输入某个参数
     *
     * @param null|int|string $name
     * @param mixed           $default
     *
     * @return mixed
     */
    public function getArg($name, $default = null);

    /**
     * 获取输入选项集合
     *
     * @return array
     */
    public function getOpts(): array;

    /**
     * 获取某一个选项参数
     *
     * @param string $name
     * @param null   $default
     *
     * @return bool|mixed|null
     */
    public function getOpt(string $name, $default = null);
}
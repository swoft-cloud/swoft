<?php

namespace Swoft\Process;

use Swoft\App;
use Swoft\Event\Event;
use Swoft\Helper\PhpHelper;
use Swoft\Server\AbstractServer;

/**
 * 自定义进程
 *
 * @uses      Process
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Process
{
    /**
     * 进程列表
     *
     * @var \Swoole\Process[]
     */
    private static $processes = [];

    /**
     * 进程ID
     *
     * @var string
     */
    private static $id;

    /**
     * 获取某个进程
     *
     * @param string $name
     *
     * @return \Swoole\Process
     */
    public static function getProcess(string $name): \Swoole\Process
    {
        if (isset(self::$processes[$name])) {
            return self::$processes[$name];
        }

        return null;
    }

    /**
     * 创建一个进程
     *
     * @param AbstractServer $server           serverd对象
     * @param string         $processName      进程名称
     * @param string         $processClassName 进程className
     *
     * @return null|\Swoole\Process
     */
    public static function create(AbstractServer $server, string $processName, string $processClassName): ?\Swoole\Process
    {
        // 不存在
        if (!class_exists($processClassName)) {
            throw new \InvalidArgumentException('自定义进程不存在，className=' . $processClassName);
        }

        /* @var AbstractProcess $processClass */
        $processClass = new $processClassName($server);
        if (!is_subclass_of($processClass, AbstractProcess::class)) {
            throw new \InvalidArgumentException('自定义进程类，不是AbstractProcess子类，className=' . $processClassName);
        }

        // 准备工作是否完成
        $isReady = $processClass->isReady();
        if ($isReady == false) {
            return null;
        }

        // 进程属性参数
        $pipe = $processClass->isPipe();
        $iout = $processClass->isInout();

        // 创建进程
        $process = new \Swoole\Process(function (\Swoole\Process $process) use ($processClass, $processName) {
            require_once BASE_PATH . '/config/reload.php';
            App::trigger(Event::BEFORE_PROCESS, null, $processName, $process, null);
            PhpHelper::call([$processClass, 'run'], [$process]);
            App::trigger(Event::AFTER_PROCESS);
        }, $iout, $pipe);

        return $process;
    }

    /**
     * 获取进程ID
     *
     * @return string
     */
    public static function getId(): string
    {
        return self::$id;
    }

    /**
     * 初始化进程ID
     *
     * @param string $id
     */
    public static function setId(string $id)
    {
        self::$id = $id;
    }
}
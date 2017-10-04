<?php

namespace Swoft\Process;

use Swoft\App;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Collector;
use Swoft\Event\Event;
use Swoft\Helper\PhpHelper;

/**
 * swoft进程
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
     * @var string
     */
    private static $id;


    public static function getProcess(string $name): \Swoole\Process
    {
        if (isset(self::$processes[$name])) {
            return self::$processes[$name];
        }

        return null;
    }

    public static function run(string $processPrefix)
    {
        $processes = Collector::$processses;
        foreach ($processes as $processName => $processAry) {
            $iout = $processAry['inout'];
            $pipe = $processAry['pipe'];
            if (!BeanFactory::hasBean($processName)) {
                echo "启动的进程不存在，processName=" . $processName;
                continue;
            }

            $processable = App::getBean($processName);

            $process = new \Swoole\Process(function (\Swoole\Process $process) use ($processable, $processPrefix, $processName) {
                App::trigger(Event::BEFORE_PROCESS, null, $processName, $process);
                PhpHelper::call([$processable, 'run'], [$process, $processPrefix]);
                App::trigger(Event::AFTER_PROCESS);
            }, $iout, $pipe);
            $process->start();
        }
    }

    /**
     * @return string
     */
    public static function getId(): string
    {
        var_dump(self::$id, '&&&&&&&');
        return self::$id;
    }

    /**
     * @param string $id
     */
    public static function setId(string $id)
    {
        self::$id = $id;
    }
}
<?php

namespace Swoft\Task;

use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Bean\BeanFactory;

/**
 * 任务处理
 *
 * @uses      Task
 * @version   2017年09月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 *
 */
class Task
{
    /**
     * 协程任务
     */
    const TYPE_COR = 'cor';

    /**
     * 异步任务
     */
    const TYPE_ASYNC = 'async';

    /**
     * 定时任务
     */
    const TYPE_CRON = 'cron';

    /**
     * @var string
     */
    private static $id;

    /**
     * 投递任务
     *
     * @param string $taskName   任务名称
     * @param string $methodName 任务job(类方法名)
     * @param array  $params     参数
     * @param string $type       类型默认协程任务投递
     * @param int    $timeout    超时时间单位秒
     *
     * @return  mixed
     */
    public static function deliver(string $taskName, string $methodName, array $params = [], string $type = self::TYPE_COR, $timeout = 3)
    {
        // httpServer
        $server = App::$server->getServer();
        $data = self::packTaskData($taskName, $methodName, $params, $type);

        // 投递协程任务
        if ($type == self::TYPE_COR) {
            $tasks[0] = $data;
            $prifleKey = 'task' . '.' . $taskName . '.' . $methodName;

            App::profileStart($prifleKey);
            $result = $server->taskCo($tasks, $timeout);
            App::profileEnd($prifleKey);
            return $result;
        }

        // 投递异步任务
        return $server->task($data);
    }

    /**
     * 投递异步多个任务
     *
     * @param array $tasks 多个任务
     *
     * <pre>
     * $tasks = [
     *  'name'   => $taskName,
     *  'method' => $methodName,
     *  'params' => $params,
     *  'type'   => $type
     * ];
     * </pre>
     *
     * @return array
     */
    public static function asyncs(array $tasks)
    {
        // httpServer
        $server = App::$server->getServer();

        $result = [];
        foreach ($tasks as $task) {
            if (!isset($task['type']) || !isset($task['name']) || !isset($task['method']) || !isset($task['params'])) {
                App::warning("投递的任务格式不完整，task=" . json_encode($task));
                continue;
            }

            $type = $task['type'];
            if ($type != self::TYPE_ASYNC) {
                App::warning("投递的不是异步任务，task=" . json_encode($task));
                continue;
            }

            $data = serialize($type);
            $result[] = $server->task($data);
        }

        return $result;
    }

    /**
     * 投递协程多个任务
     *
     * @param array $tasks 多个任务
     *
     * <pre>
     * $tasks = [
     *  'name'   => $taskName,
     *  'method' => $methodName,
     *  'params' => $params,
     *  'type'   => $type
     * ];
     * </pre>
     *
     * @return array
     */
    public static function cors(array $tasks)
    {
        // httpServer
        $server = App::$server->getServer();

        $taskCos = [];
        foreach ($tasks as $task) {
            if (!isset($task['type']) || !isset($task['name']) || !isset($task['method']) || !isset($task['params'])) {
                App::warning("投递的任务格式不完整，task=" . json_encode($task));
                continue;
            }

            $type = $task['type'];
            if ($type != self::TYPE_ASYNC) {
                App::warning("投递的不是协程任务，task=" . json_encode($task));
                continue;
            }

            $taskCos[] = serialize($task);
        }

        $result = [];
        if (!empty($taskCos)) {
            $result = $server->taskCo($tasks);
        }
        return $result;
    }

    /**
     * 执行任务
     *
     * @param string $taskName   任务名称
     * @param string $methodName 任务job(类方法名)
     * @param array  $params     参数
     *
     * @return  mixed
     */
    public static function run(string $taskName, string $methodName, array $params)
    {
        if (!BeanFactory::hasBean($taskName)) {
            //            App::error("task不存在，taskName=" . $taskName);
            return false;
        }

        $task = App::getBean($taskName);
        if (!method_exists($task, $methodName)) {
            //            App::error("task执行的job方法不存在，taskName=" . $taskName." methodName=".$methodName);
            return false;
        }

        $profileKey = $taskName . "-" . $methodName;
        //        App::profileStart($profileKey);
        $result = $task->$methodName(...$params);
        //        App::profileEnd($profileKey);

        return $result;
    }

    /**
     * @return string
     */
    public static function getId(): string
    {
        return self::$id;
    }

    /**
     * @param string $id
     */
    public static function setId(string $id)
    {
        self::$id = $id;
    }

    /**
     * 任务数据打包
     *
     * @param string $taskName   任务名称
     * @param string $methodName 任务job(类方法名)
     * @param array  $params     参数
     * @param string $type       类型默认协程任务投递
     *
     * @return  string
     */
    private static function packTaskData(string $taskName, string $methodName, array $params, string $type = self::TYPE_COR)
    {
        $task = [
            'name'   => $taskName,
            'method' => $methodName,
            'params' => $params,
            'type'   => $type
        ];

        // 不是定时任务，传递logid和spanid
        if ($type !== self::TYPE_CRON) {
            $task['logid'] = RequestContext::getLogid();
            $task['spanid'] = RequestContext::getSpanid();
        }
        return serialize($task);
    }
}
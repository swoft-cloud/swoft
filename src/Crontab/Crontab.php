<?php

namespace Swoft\Crontab;

use Swoft\Base\ApplicationContext;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Collector;
use Swoft\Memory\Table;
use Swoft\App;

/**
 * crontab定时任务
 * @Bean("crontab")
 *
 * @uses      Crontab
 * @version   2017年09月15日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Crontab
{
    /**
     * @const 任务未执行态
     */
    const NORMAL = 0;

    /**
     * @const 任务完成态
     */
    const FINISH = 1;

    /**
     * @const 任务运行态
     */
    const START = 2;

    /**
     * @var array $task corntab任务
     */
    private $task;

    /**
     * 创建配置表
     *
     * @return bool
     */
    public function init(): bool
    {
         // 非cli命令行
        $context = ApplicationContext::getContext();
        if($context == ApplicationContext::CONSOLE){
            return false;
        }
        $serverSetting = App::$server->getServerSetting();
        $cronable = (int)$serverSetting['cronable'];
        if ($cronable !== 1) {
            return false;
        }
        $this->initTasks();
        $this->initLoad();
        return true;
    }

    /**
     * 初始化Tasks任务
     *
     * @return array
     */
    private function initTasks(): array
    {
        $tasks = Collector::$crontab;

        if (!empty($tasks)) {
            $tasks = array_column($tasks, 'crons');
        }

        return $this->setTasks($tasks);
    }

    /**
     * 初始化数据表
     *
     * @return bool
     */
    public function initLoad(): bool
    {
        $tasks = $this->getTasks();

        if (count($tasks) <= 0) {
            return false;
        }

        foreach ($tasks as $tasksIndex => $taskItem) {
            foreach ($taskItem as $taskIndex => $task) {
                $this->checkTaskCount();
                $time = time();
                $key = $this->getKey($task['cron'], $task['task'], $task['method']);
                // 防止重复写入任务
                if (!$this->getOriginTable()->exist($key)) {
                    $this->getOriginTable()->set($key, [
                        'rule'       => $task['cron'],
                        'taskClass'  => $task['task'],
                        'taskMethod' => $task['method'],
                        'add_time'   => $time
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * 检测crontab任务数量
     *
     * @return bool
     */
    private function checkTaskCount(): bool
    {
        if (!isset($i)) {
            static $i = 0;
        }

        $i++;
        if ($i > TableCrontab::$taskCount) {
            throw new \InvalidArgumentException('The crontab task::' . $i . ' exceeds the threshold::' . TableCrontab::$taskCount);
        }

        return true;
    }

    /**
     * 设置crontab任务配置
     *
     * @param array $tasks 任务配置
     *
     * @return array
     */
    public function setTasks(array $tasks): array
    {
        $this->task = $tasks;

        return $this->getTasks();
    }

    /**
     * 更新要执行的task
     */
    public function checkTask()
    {
        $this->cleanRunTimeTable();
        $this->loadTableTask();
    }

    /**
     * 清理执行任务表
     */
    private function cleanRunTimeTable()
    {
        foreach ($this->getRunTimeTable()->table as $key => $value) {
            if ($value['runStatus'] == self::FINISH) {
                $this->getRunTimeTable()->del($key);
            }
        }
    }

    /**
     * 获取配置任务列表
     *
     * @return array
     */
    public function getTasks(): array
    {
        return $this->task;
    }

    /**
     * 获取原始数据表
     *
     * @return Table
     */
    public function getOriginTable(): Table
    {
        return TableCrontab::getInstance()->getOriginTable();
    }

    /**
     * 运行的数据表
     *
     * @return Table
     */
    public function getRunTimeTable(): Table
    {
        return TableCrontab::getInstance()->getRunTimeTable();
    }

    /**
     * 获取key值
     *
     * @param string $rule       规则
     * @param string $taskClass  任务类
     * @param string $taskMethod 任务方法
     * @param string $min        分
     * @param string $sec        时间戳
     *
     * @return string
     */
    private function getKey(string $rule, string $taskClass, string $taskMethod, $min = '', $sec = ''): string
    {
        return md5($rule . $taskClass . $taskMethod . $min . $sec);
    }

    /**
     * 获取内存中的任务信息
     */
    public function loadTableTask()
    {
        $originTableTasks = $this->getOriginTable()->table;
        if (count($originTableTasks) > 0) {
            $time = time();
            $this->checkTaskQueue(true);
            foreach ($originTableTasks as $id => $task) {
                $parseResult = ParseCrontab::parse($task['rule'], $time);
                if ($parseResult === false) {
                    throw new \InvalidArgumentException(ParseCrontab::$error);
                } elseif (!empty($parseResult) && is_array($parseResult)) {
                    $this->initRunTimeTableData($task, $parseResult);
                }
            }
        }
    }

    /**
     * 初始化runTimeTable数据
     *
     * @param array $task        任务
     * @param array $parseResult 解析crontab命令规则结果
     *
     * @return bool
     */
    private function initRunTimeTableData(array $task, array $parseResult): bool
    {
        $runTimeTableTasks = $this->getRunTimeTable()->table;

        $min = date('YmdHi');
        $sec = strtotime(date('Y-m-d H:i'));

        foreach ($parseResult as $time) {
            $this->checkTaskQueue(false);
            $key = $this->getKey($task['rule'], $task['taskClass'], $task['taskMethod'], $min, $time + $sec);
            $runTimeTableTasks->set($key, [
                'taskClass'  => $task['taskClass'],
                'taskMethod' => $task['taskMethod'],
                'minte'      => $min,
                'sec'        => $time + $sec,
                'runStatus'  => self::NORMAL
            ]);
        }

        return true;
    }

    /**
     * 检测crontab队列数量
     *
     * @param bool $reStart 是否重新开始检测
     *
     * @return bool
     */
    private function checkTaskQueue(bool $reStart): bool
    {
        if (!isset($i)) {
            static $i = 0;
        }

        if ($reStart) {
            $i = 0;
        }

        $i++;

        if ($i > TableCrontab::$taskQueue) {
            throw new \InvalidArgumentException('The crontab task-queue::' . $i . ' exceeds the threshold::' . TableCrontab::$taskQueue);
        }

        return true;
    }

    /**
     * 获取要执行的任务
     *
     * @return array
     */
    public function getExecTasks(): array
    {
        $data = [];
        $runTimeTableTasks = $this->getRunTimeTable()->table;
        if (count($runTimeTableTasks) <= 0) {
            return $data;
        }

        $min = date('YmdHi');

        foreach ($runTimeTableTasks as $key => $value) {
            if ($value['minte'] == $min) {
                if (time() == $value['sec'] && $value['runStatus'] == self::NORMAL) {
                    $data[] = [
                        'key'        => $key,
                        'taskClass'  => $value['taskClass'],
                        'taskMethod' => $value['taskMethod'],
                    ];
                }
            }
        }

        foreach ($data as $item) {
            $this->startTask($item['key']);
        }

        return $data;
    }

    /**
     * 开始任务
     *
     * @param int $key 主键
     *
     * @return bool
     */
    public function startTask($key)
    {
        return $this->getRunTimeTable()->set($key, ['runStatus' => self::START]);
    }

    /**
     * 完成任务
     *
     * @param int $key 主键
     *
     * @return bool
     */
    public function finishTask($key)
    {
        return $this->getRunTimeTable()->set($key, ['runStatus' => self::FINISH]);
    }
}

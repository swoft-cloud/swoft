<?php

namespace Swoft\Crontab;

use Swoft\Crontab\ParseCrontab;
use Swoft\Memory\Table\Table;
use Swoft\Bean\Collector;
use Swoft\Bean\Annotation\Bean;
use Swoft\App;

/**
 *
 * crontab任务列表
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
     * @var string $key 内存表主键
     */
    private static $key = '';

    /**
     * 创建配置表
     */
    public function init()
    {
        $this->initTasks();
        $this->initLoad();
    }

    /**
     * 初始化Tasks任务
     */
    private function initTasks()
    {
        $tasks = Collector::$crontab;

        if (!empty($tasks)) {
            $tasks = array_column($tasks, 'crons');
        }

        return $this->setTasks($tasks);
    }

    /**
     * 初始化数据表
     */
    public function initLoad()
    {
        $tasks = $this->getTasks();

        if (count($tasks) <= 0)
        {
            return false;
        }

        foreach ($tasks as $tasksIndex => $taskItem) {
           foreach ($taskItem as $taskIndex => $task) {
                $time = time();
                $key = $this->getKey($task['cron'], $task['task'], $task['method']);
                // 防止重复写入任务
                if (!$this->getOriginTable()->exist($key)) {
                    $this->getOriginTable()->set($key, [
                        'rule'    => $task['cron'],
                        'taskClass' => $task['task'],
                        'taskMethod' => $task['method'],
                        'add_time'=> $time
                    ]);
                }
           }
        }
    }

    /**
     * 设置crontab任务配置
     *
     * @param array $tasks 任务配置
     *
     * @return array
     */
    public function setTasks(array $tasks)
    {
        $this->task = $tasks;
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
     */
    public function getTasks()
    {
        return $this->task;
    }
 
    /**
     * 获取原始数据表
     *
     * @return \Swoole\Table | null
     */
    public function getOriginTable()
    {
        return TableCrontab::getInstance()->getOriginTable();
    }

    /**
     * 运行的数据表
     *
     * @return \Swoole\Table | null
     */
    public function getRunTimeTable()
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
     * @return int
     */
    private function getKey(string $rule, string $taskClass, string $taskMethod, $min = '', $sec = '')
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
            foreach ($originTableTasks as $id => $task) {
                $parseResult = ParseCrontab::parse($task['rule'], $time);
                if ($parseResult === false) {
                    throw new \InvalidArgumentException(ParseResult::$error);
                } elseif (!empty($parseResult)) {
                    $min = date('YmdHi'); 
                    $sec = strtotime(date('Y-m-d H:i'));
                    $runTimeTableTasks = $this->getRunTimeTable()->table;
                    foreach ($parseResult as $time) {
                        $key = $this->getKey($task['rule'], $task['taskClass'], $task['taskMethod'], $min, $time + $sec);
                        $runTimeTableTasks->set($key, [
                            'taskClass'  => $task['taskClass'],
                            'taskMethod' => $task['taskMethod'],
                            'minte'      => $min,
                            'sec'        => $time + $sec,
                            'runStatus'  => self::NORMAL
                        ]); 
                    }
                } 
            }
        }
    }

    /**
     * 获取要执行的任务
     *
     * @return array
     */
    public function getExecTasks() : Array
    {
        $data = [];
        if (count($this->getRunTimeTable()->table) <= 0) {
          return $data;
        }

        $min = date('YmdHi');

        foreach ($this->getRunTimeTable()->table as $key => $value) {
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

        foreach($data as $item)
        {
            $this->startTask($item['key']);
        }

        return $data;
    }

    /**
     * 开始任务
     *
     * @param int $key 主键
     *
     * @return boolean
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
     * @return boolean
     */
    public function finishTask($key)
    {
       return $this->getRunTimeTable()->set($key, ['runStatus' => self::FINISH]); 
    }
}

<?php

namespace Swoft\Crontab;

use Swoft\Crontab\ParseCrontab;
use Swoft\Memory\Table\Table;

/**
 *
 * crontab任务列表
 *
 * @uses      Crontab
 * @version   2017年09月15日
 * @author    Caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
*/
class Crontab
{
    /**
     * @const 内存表大小
     */
    const TABLE_SIZE = 1024;

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
     * @var Crontab $instance 单例
     */
    private static $instance = null;

    /**
     * @var \Swoft\Memory\Table $originTable 内存任务表
     */
    private $originTable;

    /**
     * @var \Swoft\Memory\Table $runTimeTable 内存运行表
     */
    private $runTimeTable;

    /**
     * @var boolean $alreadyInit 是否已经初始化内存表结构
     */
    private $alreadyInit = false;

    /**
     * @var boolean $alreadyInitData 是否初始化内存表数据
     */
    private $alreadyInitData = false;

    /**
     * @var array $originStruct 任务表结构
     */
    private $originStruct = [
        'rule'       => [\Swoole\Table::TYPE_STRING, 100],
        'taskClass'  => [\Swoole\Table::TYPE_STRING, 255],
        'taskMethod' => [\Swoole\Table::TYPE_STRING, 255],
        'add_time'   => [\Swoole\Table::TYPE_STRING, 11]
    ];

    /**
     * @var array $runTimeStruct 运行表结构
     */
    private $runTimeStruct = [
        'taskClass'  => [\Swoole\Table::TYPE_STRING, 255],
        'taskMethod' => [\Swoole\Table::TYPE_STRING, 255],
        'minte'      => [\Swoole\Table::TYPE_STRING, 20],
        'sec'        => [\Swoole\Table::TYPE_STRING, 20],
        'runStatus'  => [\Swoole\TABLE::TYPE_INT, 4]
    ];
    
    /**
     * @var array $task corntab任务
     */
    private $task;

    /**
     * @var int $key 内存表主键
     */
    private static $key = 0;

    /**
     * 私有化方法
     */
    private function __construct(){}

    /**
     * 拒绝克隆
     */
    private function __clone(){}

    /**
     * 获取crontab实例
     *
     * @return Crontab
     */
    public static function getInstance() : Crontab
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * 创建配置表
     *
     * return boolean
     */
    public function init() : bool
    {
        if ($this->alreadyInit) {
           return false; 
        }

        $this->alreadyInit = true;
        
        return $this->createOriginTable() && $this->createRunTimeTable();
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
     * 创建originTable
     *
     * @return bool
     */
    private function createOriginTable() : bool
    {
        $this->originTable = new Table('origin', self::TABLE_SIZE, $this->originStruct);

        return $this->originTable->create();
    }

    /**
     * 创建runTimeTable
     *
     * @return bool
     */
    private function createRunTimeTable() : bool
    {
        $this->runTimeTable = new Table('runTime', self::TABLE_SIZE, $this->runTimeStruct);

        return $this->runTimeTable->create();
    }

    /**
     * 初始化数据表
     *
     * @return bool
     */
    public function initLoad() : bool
    {
        $tasks = $this->getTasks();

        if (count($tasks) <= 0)
        {
            return false;
        }

        if (!$this->alreadyInitData) {
            foreach ($tasks as $index => $taskItem) {
                $task = array_pop($taskItem);
                $time = time();
                $this->originTable->set($index, [
                    'rule'    => $task['cron'],
                    'taskClass' => $task['task'],
                    'taskMethod' => $task['method'],
                    'add_time'=> $time
                ]);
            }

            $this->alreadyInitData = true;
        }

        return true;
    }

    /**
     * 更新要执行的task
     */
    public function checkTask()
    {
        $this->cleanRunTimeTable();
        $this->loadTableTask();
    }

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
        return $this->originTable; 
    }

    /**
     * 运行的数据表
     *
     * @return \Swoole\Table | null
     */
    public function getRunTimeTable()
    {
        return $this->runTimeTable;
    }

    /**
     * 获取key值
     *
     * @return int
     */
    private function getkey()
    {
        return ++self::$key; 
    }

    /**
     * 获取内存中的任务信息
     *
     * return boolean
     */
    public function loadTableTask() : bool
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
                       $runTimeTableTasks->set($this->getkey(), [
                            'taskClass' => $task['taskClass'],
                            'taskMethod' => $task['taskMethod'],
                            'minte'     => $min,
                            'sec'       => $time + $sec,
                            'runStatus' => self::NORMAL
                       ]); 
                   }
                } 
            }
        }

        return true;
    }

    /**
     * 获取要执行的任务
     *
     * @return array
     */
    public function getExecTasks() : Array
    {
        $data = [];
        if (count($this->getRunTimeTable()) <= 0) {
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

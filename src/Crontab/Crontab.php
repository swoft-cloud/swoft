<?php

namespace Swoft\Crontab;

use Swoft\Crontab\ParseCrontab;

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

    /** 工作进程数 */
    const TASKER_NUM = 2;

    /** 表大小 */
    const PROCESS_SIZE = 1024;

    /** 并发数 */
    const MAX_CONCURRENT = 1024;

    const NORMAL = 0;

    const FINISH = 1;

    const START = 2;

    /** @var 实例  */
    private static $instance = null;

    /** @var \Swoole\Table */
    private $originTable;

    /** @var \Swoole\Table */
    private $runTimeTable;

    /** @var 是否已初始化 */
    private $alreadyInit = false;

    /** @var 是否已加载数据 */
    private $alreadInitData = false;

    /** @var crontab配置信息  */
    private $taskConfig = [];

    /** @var array originTable's struct */
    private $originStruct = [
        'rule' => [\Swoole\Table::TYPE_STRING, 50],
        'execute' => [\Swoole\Table::TYPE_STRING, 255],
        'add_time' => [\Swoole\Table::TYPE_STRING, 20]
    ];

    /** @var array runTimeTable's struct */
    private $runTimeStruct = [
        'execute' => [\Swoole\Table::TYPE_STRING, 255],
        'minte' => [\Swoole\Table::TYPE_STRING, 20],
        'sec' => [\Swoole\Table::TYPE_STRING, 20],
        'runStatus' => [\Swoole\TABLE::TYPE_INT, 4]
    ];
    
    /** 任务 */
    private $task;

    /** 键 */
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
    ┊*/
    public function init() : bool
    {
        if ($this->alreadyInit) {
           return false; 
        }

        $this->alreadyInit = true;
        
        return $this->_createOriginTable() && $this->_createRunTimeTable();
    }

    /**
     * 设置crontab任务配置
     *
     * @param array $taskConfig 任务配置
     *
     * @return array
     */
    public function setTaskConfig(array $taskConfig)
    {
        if (isset($taskConfig['task']) && !empty($taskConfig['task'])) {
            $this->task = $taskConfig['task'];
        }

        return $this->getTaskConfig();
    }

    /**
     * 获取crontab任务配置
     *
     * @return array
     */
    public function getTaskConfig() : array
    {
        return $this->taskConfig;
    }

    /**
     * 创建originTable
     *
     * @return bool
     */
    private function _createOriginTable() : bool
    {
        $this->originTable = new \Swoole\Table(self::PROCESS_SIZE * 2);
        foreach ($this->originStruct as $key => $v) {
            $this->originTable->column($key, $v[0], $v[1]);
        }

        return $this->originTable->create();
    }

    /**
     * 创建runTimeTable
     *
     * @return bool
     */
    private function _createRunTimeTable() : bool
    {
        $this->runTimeTable = new \Swoole\Table(self::PROCESS_SIZE * 2);
        foreach ($this->runTimeStruct as $key => $v) {
            $this->runTimeTable->column($key, $v[0], $v[1]);
        }

        return $this->runTimeTable->create();
    }

    /**
     * 初始化数据表
     *
     * @return bool
     */
    public function initLoad() : bool
    {
        $tasks = $this->getTaskList();

        if (count($tasks) <= 0)
        {
            return false;
        }

        if (!$this->alreadInitData) {
            foreach ($tasks as $index => $taskItem) {
                $time = time();
                $this->originTable->set($index, [
                    'rule'    => $taskItem['rule'],
                    'execute' => $taskItem['execute'],
                    'add_time'=> $time
                ]);
            }

            $this->alreadInitData = true;
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
        foreach ($this->getRunTimeTable() as $key => $value) {
            if ($value['runStatus'] == self::FINISH) {
                $this->getRunTimeTable->del($key);
            }
        }
    }

    /**
     * 获取配置任务列表
     */
    public function getTaskList()
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
        $originTableTasks = $this->getOriginTable();
        if (count($originTableTasks) > 0) {
            $time = time();
            foreach ($originTableTasks as $id => $task) {
                $parseResult = ParseCrontab::parse($task['rule'], $time);
                if ($parseResult === false) {
                     throw new \InvalidArgumentException($parseResult::$error);
                } elseif (!empty($parseResult)) {
                   $min = date('YmdHi'); 
                   $sec = strtotime(date('Y-m-d H:i'));
                   $runTimeTableTasks = $this->getRunTimeTable();
                   foreach ($parseResult as $time) {
                       $runTimeTableTasks->set($this->getkey(), [
                            'execute'   => $task['execute'],
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
    public function getExecTasks()
    {
        $data = [];
        if (count($this->getRunTimeTable()) <= 0) {
          return $data;
        }

        $min = date('YmdHi');

        foreach ($this->getRunTimeTable() as $key => $value) {
            if ($value['minte'] == $min) {
                if (time() == $value['sec'] && $value['runStatus'] == self::NORMAL) {
                    $exeParseArr = preg_split("/[\s]/", $value['execute']);
                    $cmd = array_shift($exeParseArr);
                    $args = empty($exeParseArr) ? [] : $exeParseArr;
                    $data[] = [
                        'key'  => $key,
                        'cmd'  => $cmd,
                        'args' => $args
                    ];
                }
            } 
        }

        foreach($data as $item)
        {
           $this->getRunTimeTable()->set($item['key'], ['runStatus' => self::START]);
        }

        return $data;
    }

    /**
     * 完成任务
     *
     * @return boolean
     */
    public function finishTask($key)
    {
       return $this->getRunTimeTable()->set($key, ['runStatus' => self::FINISH]); 
    }
}

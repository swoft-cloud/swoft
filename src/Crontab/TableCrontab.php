<?php

namespace Swoft\Crontab;

use Swoft\Crontab\ParseCrontab;
use Swoft\Memory\Table;
use Swoft\Bean\Collector;
use Swoft\Bean\Annotation\Bean;
use Swoft\App;

/**
 *
 * crontab内存表结构
 *
 * @uses      TableCrontab
 * @version   2017年10月26日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
*/
class TableCrontab
{
    /**
     * @const 内存表大小
     */
    const TABLE_SIZE = 1024;

    /**
     * @var $this $instance 实例对象
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
     * 创建配置表
     */
    public static function init()
    {
        self::getInstance();
        self::$instance->initTables();
    }

    /**
     * 获取实例对象
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * 初始化任务表
     */
    private function initTables()
    {
       return $this->createOriginTable() && $this->createRunTimeTable();
    }

    /**
     * 创建originTable
     *
     * @return bool
     */
    private function createOriginTable() : bool
    {
        $this->setOriginTable(new Table('origin', self::TABLE_SIZE, $this->originStruct));

        return $this->getOriginTable()->create();
    }

    /**
     * 创建runTimeTable
     *
     * @return bool
     */
    private function createRunTimeTable() : bool
    {
        $this->setRunTimeTable(new Table('runTime', self::TABLE_SIZE, $this->runTimeStruct));

        return $this->getRunTimeTable()->create();
    }

    /**
     * 设置内存任务表实例
     *
     * @param Table $table 内存表
     */
    public function setOriginTable(Table $table)
    {
        $this->originTable = $table;
    }

    /**
     * 获取内存任务表实例
     */
    public function getOriginTable()
    {
        return $this->originTable;
    }

    /**
     * 设置执行任务表实例
     *
     * @param Table $table 执行任务表
     */
    public function setRunTimeTable(Table $table)
    {
        $this->runTimeTable = $table;
    }

    /**
     * 获取执行任务表实例
     */
    public function getRunTimeTable()
    {
        return $this->runTimeTable;
    }
}

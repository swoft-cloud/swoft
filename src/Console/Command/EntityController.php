<?php

namespace Swoft\Console\Command;

use Swoft\Console\ConsoleCommand;
use Swoft\Db\EntityGenerator\GeneratorEntity;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Bean\BeanFactory;
use Swoft\App;

/**
 * the group command list of database entity
 *
 * @uses      EntityController
 * @version   2017年10月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class EntityController extends ConsoleCommand
{

    /**
     * 实体实例
     */
    private $generatorEntity;

    /**
     * 初始化
     *
     * @param Input  $input  输入
     * @param Output $output 输出
     */
    public function __construct(Input $input, Output $output)
    {
        parent::__construct($input, $output);
        
        // 初始化相关内容
       BeanFactory::reload();
       $pool = App::getBean('dbSlave');
       $syncDbConnect = $pool->createConnect();
       $this->generatorEntity = new GeneratorEntity($syncDbConnect);
    }

    /**
     * auto create entity by table structure
     *
     * @usage
     * entity:{command} [arguments] [options]
     *
     * @options
     * --ignore
     *
     * @example
     * php bin/swoft entity:create
     */
    public function createCommand()
    {
        $database = null;
        $tablesenabled = $tablesdisabled = [];

        $this->parseDatabaseCommand($database);
        $this->parseEnableTablesCommand($tablesenabled);
        $this->parseDisableTablesCommand($tablesdisabled);

        if (empty($database)) {
            $this->output->writeln('databases doesn\'t not empty!');
        } else {
            $this->generatorEntity->db = $database;
            $this->generatorEntity->tablesenabled = $tablesenabled;
            $this->generatorEntity->tablesdisabled = $tablesdisabled;
            $this->generatorEntity->execute();
        }
    }

    /**
     * 解析需要扫描的数据库
     *
     * @param &$database 需要扫描的数据库
     */
    private function parseDatabaseCommand(&$database)
    {
        if ($this->input->hasSOpt('d') || $this->input->hasLOpt('database')) {
        $database = $this->input->hasSOpt('d') ? $this->input->getShortOpt('d') : $this->input->getLongOpt('database');
        }
    }

    /**
     * 解析需要扫描的table
     *
     * @param array &$tablesenabled 需要扫描的表
     */
    private function parseEnableTablesCommand(&$tablesenabled)
    {
        if ($this->input->hasSOpt('i') || $this->input->hasLOpt('include')) {
        $tablesenabled = $this->input->hasSOpt('i') ? $this->input->getShortOpt('i') : $this->input->getLongOpt('include');
        $tablesenabled = !empty($tablesenabled) ? explode(',', $tablesenabled) : [];
        }

        // 参数优先级大于选项
        if (!empty($this->input->getArg(0))) {
            $tablesenabled = [$this->input->getArg(0)];
        }
    }

    /**
     * 解析不需要扫描的table
     *
     * @param array &$tablesdisabled 不需要扫描的表
     */
    private function parseDisableTablesCommand(&$tablesdisabled)
    {
        if ($this->input->hasSOpt('e') || $this->input->hasLOpt('exclude')) {
        $tablesdisabled = $this->input->hasSOpt('e') ? $this->input->getShortOpt('e') : $this->input->getLongOpt('exclude');
        $tablesdisabled = !empty($tablesdisabled) ? explode(',', $tablesdisabled) : [];
        }
    }
}

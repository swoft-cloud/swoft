<?php

namespace Swoft\Db\EntityGenerator;

use Swoft\Db\EntityManager;

/**
 * 生成实体操作类
 *
 * @uses      GeneratorEntity 
 * @version   2017年11月06日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class GeneratorEntity extends AbstractGeneratorEntity implements IGeneratorEntity
{
    /**
     * @var string $db 数据库
     */
    private $db = null;

    /**
     * @var array $tablesenabled 操作的表
     */
    private $tablesenabled = [];

    /**
     * @var array $tablesenabled 不操作的表
     */
    private $tablesdisabled = [];

    /**
     * @var array $tables 实体表
     */
    private $tables = [];

    /**
     * @const string SchemaTables表
     */
    const SCHEMA_TABLES = 'information_schema.`tables`';

    /**
     * @const string SchemaColumn表
     */
    const SCHEMA_COLUMN = 'information_schema.`columns`';

    /**
     * 开始执行生成实体
     */
    public function execute()
    {
        $tables =  $this->getSchemaTables();
        foreach ($tables as $table) {
            $columns = $this->getTableColumns($table['name']);
            $this->parseProperty($table['name'], $table['comment'], $columns);
        }
    }

    /**
     * 获取当前db的所有表
     *
     * @return array
     */
    public function getSchemaTables(): array
    {
        if (empty($this->db)) {
            return [];
        }
        $schemaTable = self::SCHEMA_TABLES;
        $where[] = "TABLE_TYPE = 'BASE TABLE'";
        $where[] = "TABLE_SCHEMA = '{$this->db}'";
        if (!empty($this->tablesenabled)) {
            $tablesenabled = array_map(function($item){
                return "'{$item}'";
            }, $this->tablesenabled);
            $where[] = 'TABLE_NAME IN (' . implode(',', $tablesenabled) . ')';
        }
        if (!empty($this->tablesdisabled)) {
            $tablesdisabled = array_map(function($item){
                return "'{$item}'";
            }, $this->tablesdisabled);
            $where[] = 'TABLE_NAME NOT IN (' . implode(',', $tablesdisabled) . ')';
        }
        $where = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : null; 

        $querySql = "SELECT `TABLE_NAME` AS `name`,`TABLE_COMMENT` as `comment` FROM {$schemaTable} {$where}";
        $this->dbHandler->prepare($querySql);
        $this->tables = $this->dbHandler->execute([]);

        return !empty($this->tables) ? $this->tables : [];
    }

    /**
     * 获取表列名
     *
     * @param string $table 表名
     *
     * @return array
     */
    public function getTableColumns(string $table): array
    {
        $schemaTable = self::SCHEMA_COLUMN;

        $where[] = "TABLE_NAME = '{$table}'";
        $where[] = "TABLE_SCHEMA = '{$this->db}'";
        $where = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : null; 

        $querySql = "SELECT `COLUMN_NAME` as `name`,`DATA_TYPE` as `type`,`CHARACTER_MAXIMUM_LENGTH` as `length`,`COLUMN_DEFAULT` as `default` ,`COLUMN_KEY` as `key`,`IS_NULLABLE` as `nullable` FROM {$schemaTable} {$where}";
        $this->dbHandler->prepare($querySql);
        $columns = $this->dbHandler->execute([]);

        return !empty($columns) ? $columns : [];
    }

    /**
     * 设置数据库
     *
     * @param string $value 数据库
     *
     * @return $this
     */
    public function setDb(string $value): self
    {
        $this->db = $value;

        return $this;
    }

    /**
     * 获取数据库
     *
     * @return string|null
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * 设置扫描的表
     *
     * @param array $value 需要扫描的表
     *
     * @return $this;
     */
    public function setTablesenabled(array $value): self
    {
        $this->tablesenabled = $value;

        return $this;
    }

    /**
     * 返回需要扫描的表
     *
     * @return array
     */
    public function getTablesenabled(): array
    {
        return $this->tablesenabled;
    }

    /**
     * 设置不需要扫描的表
     *
     * @param array 不需要扫描的表
     *
     * @return $this;
     */
    public function setTablesdisabled(array $value): self
    {
        $this->tablesdisabled = $value;

        return $this;
    }

    /**
     * 返回不需要扫描的表
     *
     * @retrun array
     */
    public function getTablesdisabled(): array
    {
        return $this->tablesdisabled;
    }

    /**
     * __get()
     * @override
     *
     * @param string $name 参数名
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        } elseif (method_exists($this, 'set'. ucfirst($name))) {
            throw new \RunTimeException('the property only access write'. get_class($this) .'::' . $name);
        } else {
            throw new \RunTimeException('unknow the property' . get_class($this) . '::' . $name);
        }
    }

    /**
     * __set()
     * @override
     *
     * @param string $name 参数名
     * @param mixed  $value 参数值
     *
     * @return self 
     */
    public function __set($name, $value): self
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        } elseif (method_exists($this, 'get'. ucfirst($name))) {
            throw new \RunTimeException('the property only access read'. get_class($this) .'::' . $name);
        } else {
            throw new \RunTimeException('unknow the property' . get_class($this) . '::' . $name);
        }
    }
}

<?php

namespace Swoft\Memory\Table;

use Swoole\Table as swTable;

/**
 * Table
 *
 * @uses      Table
 * @version   2017年10月25日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Table implements ITable
{
    /**
     * @var swTable $table 内存表实例
     */
    private $table = null;

    /**
     * @var string $name 内存表名
     */
    private $name = '';

    /**
     * @var int $size table大小
     */
    private $size = 0;

    /**
     * @var array $column 列数组
     * [
     *  'field' => ['type', length]
     * ]
     */
    private $columns = [];

    public function __construct(swTable $table = null, string $name='', int $size = 0, array $columns = [])
    {
        $this->table = $table;
        $this->size = $size;
        $this->columns = $columns;
    }

    /**
     * 设置内存表实例
     *
     * @param swTable $table 内存表实例
     *
     * @return $this
     */
    public function setTable(swTable $table) : Table
    {
        $this->table = $table;

        return $this;
    }

    /**
     * 获取内存表实例
     *
     * @return $mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * 设置内存表名
     *
     * @param string $name 内存表名
     *
     * @return $this
     */
    public function setName(string $name) : Table
    {
        $this->name = $name;

        return $this;
    }

    /**
     * 返回内存表名
     *
     * @return $mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 设置内存表大小
     *
     * @param int $size 内存表大小
     *
     * @return $this 
     */
    public function setSize(int $size) : Table
    {
        $this->size = $size;

        return $this;
    }

    /**
     * 获取内存表大小
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * 设置内存表字段结构
     *
     * @param array $column 字段数组
     *
     * @return $this;
     */
    public function setColumns(array $columns) : Table
    {
        $this->columns = $columns;

        foreach ($this->columns as $filed => $fieldValue) {
            $args = array_merge([$field], $fieldvalue);
            $this->column(...$args);
        }

        return $this;
    }

    /**
     * 返回列字段数组
     *
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * 内存表增加一列
     *
     * @param string $name 列名
     * @param string $type 类型
     * @param string $size 最大长度，单位为字节
     */
    public function column(string $name, int $type, int $size = 0)
    {
        switch ($type) {
            case self::TYPE_INT:
                if (!in_array($size, [self::ONE_INT_LENGTH, self::TWO_INT_LENGTH, self::FOUR_INT_LENGTH, self::EIGHT_INT_LENGTH])) {
                    $size = 4;
                }
                break;
            case self::TYPE_STRING:
                    if ($size < 0) {
                        throw new \RuntimeException('Size not be allow::' . $size);
                    }
                break;
            case self::TYPE_FLOAT:
                    $size = 8;
                break;
            default:
                throw new \RuntimeException('Undefind Column-Type::' . $type);
        }

        return $this->table->column($name, $type, $size);
    }

    /**
     * 创建内存表
     */
    public function create()
    {
        return $this->table->create();
    }

    /**
     * 设置行数据
     *
     * @param string $key   索引键
     * @param array  $array 数据
     */
    public function set(string $key, array $array)
    {
        return $this->table->set($key, $array);
    }

    /**
     * 原子自增操作
     *
     * @param string    $key    索引键
     * @param string    $column 列名
     * @param int|float $incrby 增量。如果列为整形，$incrby必须为int型，如果列为浮点型，$incrby必须为float类型
     */
    public function incr(string $key, string $column, $incrby = 1)
    {
        return $this->table->incr($key, $column, $incrby);
    }

    /**
     * 原子自减操作
     *
     * @param string    $key    索引键
     * @param string    $column 列名
     * @param int|float $incrby 增量。如果列为整形，$incrby必须为int型，如果列为浮点型，$incrby必须为float类型
     */
    public function decr(string $key, string $column, $incrby = 1)
    {
        return $this->table->decr($key, $column, $incrby);
    }

    /**
     * 获取一行数据
     * 
     * @param string $key   索引键
     * @param string $field 列名 
     */
    public function get(string $key, $field = null)
    {
        return $this->table->get($key, $field);
    }

    /**
     * 检查table中是否存在某一个key
     *
     * @param string $key 索引键
     */
    public function exist(string $key)
    {
        return $this->table->exist($key);
    }

    /**
     * 删除数据
     *
     * @param string $key 索引键
     */
    public function del(string $key)
    {
        return $this->table->del($key);
    }

    /**
     * invoke
     *
     * @param string $method 方法名字
     * @param array  参数
     *
     * @return mixed
     */
    public function __call(string $method, array $args = [])
    {
        if (method_exists($this->table, $method)) {
            return $this->$method(...$args);
        }
        throw new \RuntimeException('Call a not exists method.'); 
    }
}

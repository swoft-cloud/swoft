<?php

namespace Swoft\Db;

/**
 * 实体模型实现类似ActiverRecord操作
 *
 * @uses      Model
 * @version   2017年09月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Model
{
    /**
     * 记录旧数据，用于更新数据对比
     *
     * @var array
     */
    private $attrs = [];


    /**
     * 插入数据
     *
     * @param bool $defer 是否延迟收包
     *
     * @return DataResult|bool 返回数据结果对象，成功返回插入ID，如果没有ID插入返回0，错误返回false
     */
    public function save($defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->save($this, $defer);
    }

    /**
     * 删除数据
     *
     * @param bool $defer 是否延迟收包
     *
     * @return DataResult|bool|int 返回数据结果对象，成功返回影响行数，如果失败返回false
     */
    public function delete($defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->delete($this, $defer);
    }

    /**
     * 根据ID删除数据
     *
     * @param mixed $id    ID
     * @param bool  $defer 是否延迟收包
     *
     * @return DataResult|bool|int DataResult|bool|int 返回数据结果对象，成功返回影响行数，如果失败返回false
     */
    public static function deleteById($id, $defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->deleteById(static::class, $id, $defer);
    }

    /**
     * 删除IDS集合数据
     *
     * @param array $ids   ID集合
     * @param bool  $defer 是否延迟收包
     *
     * @return DataResult|bool|int 返回数据结果对象，成功返回影响行数，如果失败返回false
     */
    public static function deleteByIds(array $ids, $defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->deleteByIds(static::class, $ids, $defer);
    }

    /**
     * 更新数据
     *
     * @param bool $defer 是否延迟收包
     *
     * @return DataResult|bool|int 返回数据结果对象，成功返回影响行数，如果失败返回false
     */
    public function update($defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->update($this, $defer);
    }

    /**
     * 实体查询
     *
     * @param bool $isMaster 是否主节点查询
     *
     * @return QueryBuilder
     */
    public function find($isMaster = false)
    {
        $executor = self::getExecutor($isMaster);
        return $executor->find($this);
    }

    /**
     * ID查找
     *
     * @param mixed $id       id值
     * @param bool  $isMaster 是否是主节点，默认从节点
     *
     * @return QueryBuilder
     */
    public static function findById($id, $isMaster = false)
    {
        $executor = self::getExecutor($isMaster);
        return $executor->findById(static::class, $id);
    }

    /**
     * ID集合查询
     *
     * @param array $ids      ID集合
     * @param bool  $isMaster 是否主节点查询
     *
     * @return QueryBuilder
     */
    public static function findByIds(array $ids, $isMaster = false)
    {
        $executor = self::getExecutor($isMaster);
        return $executor->findByIds(static::class, $ids);
    }

    /**
     * 返回查询器，自定义查询
     *
     * @param bool $isMaster 是否主节点
     *
     * @return QueryBuilder
     */
    public static function query($isMaster = false)
    {
        return EntityManager::getQuery(static::class, $isMaster, true);
    }


    /**
     * 返回数据执行器
     *
     * @param bool $isMaster 是否主节点
     *
     * @return Executor
     */
    private static function getExecutor($isMaster = false)
    {
        $queryBuilder = EntityManager::getQuery(static::class, $isMaster, true);
        $executor = new Executor($queryBuilder, static::class);
        return $executor;
    }

    /**
     * @return array
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }

    /**
     * @param array $attrs
     */
    public function setAttrs(array $attrs)
    {
        $this->attrs = $attrs;
    }
}
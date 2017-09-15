<?php

namespace Swoft\Db;

use Swoft\Web\AbstractResult;

/**
 *
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
     * @var array
     */
    private $attrs = [];


    /**
     * @param bool $defer
     *
     * @return DataResult|bool
     */
    public function save($defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->save($this, $defer);
    }

    /**
     *
     * @param bool $defer
     *
     * @return DataResult|bool|int
     */
    public function delete($defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->delete($this, $defer);
    }

    /**
     * @param      $id
     * @param bool $defer
     *
     * @return DataResult|bool|int
     */
    public static function deleteById($id, $defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->deleteById(static::class, $id, $defer);
    }

    /**
     * @param array $ids
     * @param bool  $defer
     *
     * @return DataResult|bool|int
     */
    public static function deleteByIds(array $ids, $defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->deleteByIds(static::class, $ids, $defer);
    }

    /**
     * @param bool $defer
     *
     * @return AbstractResult|bool
     */
    public function update($defer = false)
    {
        $executor = self::getExecutor(true);
        return $executor->update($this, $defer);
    }

    /**
     * 实体查询
     *
     * @param bool $isMaster
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
     * @param array $ids
     * @param bool  $isMaster
     *
     * @return QueryBuilder
     */
    public static function findByIds(array $ids, $isMaster = false)
    {
        $executor = self::getExecutor($isMaster);
        return $executor->findByIds(static::class, $ids);
    }

    /**
     * @param bool $isMaster
     *
     * @return QueryBuilder
     */
    public static function query($isMaster = false)
    {
        return EntityManager::getQuery(static::class, $isMaster, true);
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

    private static function getExecutor($isMaster = false)
    {
        $queryBuilder = EntityManager::getQuery(static::class, $isMaster, true);
        $executor = new Executor($queryBuilder, static::class);
        return $executor;
    }
}
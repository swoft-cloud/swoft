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
     * @return AbstractResult|bool
     */
    public function save($defer = false)
    {
        $executor = $this->getExecutor(true);
        return $executor->save($this, $defer);
    }

    /**
     *
     * @param bool $defer
     * @return AbstractResult|bool
     */
    public function delete($defer = false)
    {
        $executor = $this->getExecutor(true);
        return $executor->delete($this, $defer);
    }

    public function deleteById($id, $defer = false)
    {
        $executor = $this->getExecutor(true);
        return $executor->deleteById(static::class, $id, $defer);
    }

    public function deleteByIds(array $ids, $defer = false)
    {
        $executor = $this->getExecutor(true);
        return $executor->deleteByIds(static::class, $ids, $defer);
    }

    /**
     * @param bool $defer
     *
     * @return AbstractResult|bool
     */
    public function update($defer = false)
    {
        $executor = $this->getExecutor(true);
        return $executor->update($this, $defer);
    }

    public function find($entity, $isMaster = false)
    {
        $executor = $this->getExecutor($isMaster);
        return $executor->find($entity);
    }

    public function findById($id, $isMaster = false)
    {
        $executor = $this->getExecutor($isMaster);
        return $executor->findById(static::class, $id);
    }

    public function findByIds(array $ids, $isMaster = false)
    {
        $executor = $this->getExecutor($isMaster);
        return $executor->findByIds(static::class, $ids);
    }

    /**
     * @param bool $isMaster
     *
     * @return QueryBuilder
     */
    public static function query($isMaster = false)
    {
        return EntityManager::getQuery(static::class, $isMaster);
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

    private function getExecutor($isMaster = false)
    {
        $queryBuilder = EntityManager::getQuery(static::class, $isMaster, true);
        $executor = new Executor($queryBuilder, static::class);
        return $executor;
    }
}
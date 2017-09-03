<?php

namespace Swoft\Db;

use Swoft\Db\Mysql\Query;
use Swoft\Db\Mysql\QueryBuilder;

/**
 *
 *
 * @uses      EntityManager
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class EntityManager implements IEntityManager
{
    /**
     * 连接
     *
     * @var AbstractConnect
     */
    private $connect;

    /**
     * 驱动
     *
     * @var string
     */
    private $driver;


    public function create($isMaster = false)
    {
        
    }

    public function beginTransaction()
    {
        $this->connect->beginTransaction();
    }

    public function commit()
    {
        $this->connect->commit();
    }

    public function rollback()
    {
        $this->connect->rollback();
    }

    public function createQuery($sql = '')
    {
        return new Query($this->connect, $sql);
    }

    public function createQueryBuilder()
    {
        return new QueryBuilder($this->connect);
    }

    public function save($entity)
    {
    }

    public function update($entity)
    {
    }

    public function delete($entity)
    {
    }

    public function findByPk($entity, ...$params)
    {
    }

    public function find($entity)
    {
    }

    public function close()
    {

    }
}
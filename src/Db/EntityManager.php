<?php

namespace Swoft\Db;

use Swoft\App;
use Swoft\Db\Mysql\Query;
use Swoft\Di\BeanFactory;
use Swoft\Pool\ConnectPool;
use Swoft\Pool\DbPool;

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
    const MASTER = "dbMaster";

    const SLAVE = "dbSlave";

    /**
     * @var ConnectPool
     */
    private $pool = null;

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

    private $executor;

    public function __construct(ConnectPool $pool)
    {
        $this->pool = $pool;
        $this->connect = $pool->getConnect();
        $this->driver = $this->connect->getDriver();
        $query = self::createQuery();
        $this->executor = new Executor($query);
    }


    public static function create($isMaster = false)
    {
        $pool = self::getPool($isMaster);
        return new EntityManager($pool);
    }

    /**
     * @param string $className
     * @param bool   $isMaster
     * @param bool   $release
     *
     * @return QueryBuilder
     */
    public static function getQuery(string $className, $isMaster = false, $release = true)
    {
        $pool = self::getPool($isMaster);
        $connect = $pool->getConnect();
        $driver = $connect->getDriver();
        $entities = App::getEntities();
        $tableName = $entities[$className]['table']['name'];

        $className = "Swoft\Db\\".$driver."\\QueryBuilder";

        /* @var QueryBuilder $query */
        $query = new $className($pool, $connect, '', $release);
        $query->from($tableName);

        return $query;
    }

    /**
     *
     * @param $isMaster
     *
     * @return ConnectPool
     */
    private static function getPool($isMaster)
    {
        $dbPoolId = self::SLAVE;
        if($isMaster){
            $dbPoolId = self::MASTER;
        }
        /* @var DbPool $dbPool*/
        $pool = App::getBean($dbPoolId);
        return $pool;
    }

    public static function createById(string $poolId)
    {
        if (!BeanFactory::hasBean($poolId)) {
            throw new \InvalidArgumentException("数据库连接池未配置，poolId=" . $poolId);
        }

        /* @var DbPool $dbPool */
        $dbPool = App::getBean($poolId);
        return new EntityManager($dbPool);
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

    /**
     * @param string $sql
     *
     * @return QueryBuilder
     */
    public function createQuery(string $sql = "")
    {
        $className = "Swoft\Db\\".$this->driver."\\QueryBuilder";
        return new $className($this->pool, $this->connect, $sql);
    }

    public function save($entity, $defer = false)
    {
        return $this->executor->save($entity, $defer);
    }

    public function delete($entity, $defer = false)
    {
        return $this->executor->delete($entity, $defer);
    }

    public function deleteById($className, $id, $defer = false)
    {
        return $this->executor->deleteById($className, $id, $defer);
    }

    public function deleteByIds($className, array $ids, $defer = false)
    {
        return $this->executor->deleteByIds($className, $ids, $defer);
    }

    public function update($entity, $defer = false)
    {
        return $this->executor->update($entity, $defer);
    }

    public function find($entity, $isMaster = false)
    {
        return $this->executor->find($entity);
    }

    public function findById($className, $id)
    {
        return $this->executor->findById($className, $id);
    }

    public function findByIds($className, array $ids)
    {
        return $this->executor->findByIds($className, $ids);
    }

    public function close()
    {
        $this->pool->release($this->connect);
    }

    /**
     * @return AbstractConnect
     */
    public function getConnect(): AbstractConnect
    {
        return $this->connect;
    }
}
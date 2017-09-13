<?php

namespace Swoft\Db;

use Swoft\App;
use Swoft\Di\BeanFactory;
use Swoft\Exception\DbException;
use Swoft\Pool\ConnectPool;
use Swoft\Pool\DbPool;

/**
 * 实体管理器
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
     * 数据库主节点连接池ID
     */
    const MASTER = "dbMaster";

    /**
     * 数据库从节点连接池ID
     */
    const SLAVE = "dbSlave";

    /**
     * 数据库驱动
     *
     * @var string
     */
    private $driver;

    /**
     * 数据库连接
     *
     * @var AbstractConnect
     */
    private $connect;

    /**
     * 实体执行器
     *
     * @var Executor
     */
    private $executor;

    /**
     * 连接池
     *
     * @var ConnectPool
     */
    private $pool = null;

    /**
     * 当前EM是否关闭
     *
     * @var bool
     */
    private $isClose = false;

    /**
     * EntityManager constructor.
     *
     * @param ConnectPool $pool
     */
    public function __construct(ConnectPool $pool)
    {
        // 初始化连接信息
        $this->pool = $pool;
        $this->connect = $pool->getConnect();
        $this->driver = $this->connect->getDriver();

        // 初始化实体执行器
        $query = self::createQuery();
        $this->executor = new Executor($query);
    }

    public static function create($isMaster = false)
    {
        $pool = self::getPool($isMaster);
        return new EntityManager($pool);
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

    /**
     * @param string $sql
     *
     * @return QueryBuilder
     */
    public function createQuery(string $sql = "")
    {
        $this->checkStatus();
        $className = "Swoft\Db\\" . $this->driver . "\\QueryBuilder";
        return new $className($this->pool, $this->connect, $sql);
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

        $className = "Swoft\Db\\" . $driver . "\\QueryBuilder";

        /* @var QueryBuilder $query */
        $query = new $className($pool, $connect, '', $release);
        $query->from($tableName);

        return $query;
    }

    public function save($entity, $defer = false)
    {
        $this->checkStatus();
        return $this->executor->save($entity, $defer);
    }

    public function delete($entity, $defer = false)
    {
        $this->checkStatus();
        return $this->executor->delete($entity, $defer);
    }

    public function deleteById($className, $id, $defer = false)
    {
        $this->checkStatus();
        return $this->executor->deleteById($className, $id, $defer);
    }

    public function deleteByIds($className, array $ids, $defer = false)
    {
        $this->checkStatus();
        return $this->executor->deleteByIds($className, $ids, $defer);
    }

    public function update($entity, $defer = false)
    {
        $this->checkStatus();
        return $this->executor->update($entity, $defer);
    }

    public function find($entity, $isMaster = false)
    {
        $this->checkStatus();
        return $this->executor->find($entity);
    }

    public function findById($className, $id)
    {
        $this->checkStatus();
        return $this->executor->findById($className, $id);
    }

    public function findByIds($className, array $ids)
    {
        $this->checkStatus();
        return $this->executor->findByIds($className, $ids);
    }

    public function beginTransaction()
    {
        $this->checkStatus();
        $this->connect->beginTransaction();
    }

    public function rollback()
    {
        $this->checkStatus();
        $this->connect->rollback();
    }

    public function commit()
    {
        $this->checkStatus();
        $this->connect->commit();
    }

    public function close()
    {
        $this->isClose = true;
        $this->pool->release($this->connect);
    }

    /**
     * @return AbstractConnect
     */
    public function getConnect(): AbstractConnect
    {
        return $this->connect;
    }

    private function checkStatus()
    {
        if ($this->isClose) {
            throw new DbException("entity manager已经关闭，不能再操作");
        }
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
        if ($isMaster) {
            $dbPoolId = self::MASTER;
        }
        /* @var DbPool $dbPool */
        $pool = App::getBean($dbPoolId);
        return $pool;
    }
}

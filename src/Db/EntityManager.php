<?php

namespace Swoft\Db;

use Swoft\App;
use Swoft\Db\Mysql\Query;
use Swoft\Db\Mysql\QueryBuilder;
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

    private $entityMapper;

    public function __construct(ConnectPool $pool)
    {
        $this->pool = $pool;
        $this->connect = $pool->getConnect();
        $this->driver = $this->connect->getDriver();
    }


    public static function create($isMaster = false)
    {
        $dbPoolId = self::SLAVE;
        if($isMaster){
            $dbPoolId = self::MASTER;
        }
        /* @var DbPool $dbPool*/
        $dbPool = App::getBean($dbPoolId);
        return new EntityManager($dbPool);
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
     * @return AbstractQueryBuilder
     */
    public function createQuery(string $sql = "")
    {
        $className = "Swoft\Db\\".$this->driver."\\QueryBuilder";
        return new $className($this->connect, $sql);
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
        $this->pool->release($this->connect);
    }
}
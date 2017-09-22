<?php

namespace Swoft\Db;

use Swoft\App;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Collector;
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
    }

    /**
     * 实例化一个实体管理器
     *
     * @param bool $isMaster 默认从节点
     *
     * @return EntityManager
     */
    public static function create($isMaster = false)
    {
        $pool = self::getPool($isMaster);
        return new EntityManager($pool);
    }

    /**
     * 实例化一个指定ID的实体管理器
     *
     * @param string $poolId 其它数据库连接池ID
     *
     * @return EntityManager
     */
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
     * 创建一个查询器
     *
     * @param string $sql sql语句，默认为空
     *
     * @return QueryBuilder
     */
    public function createQuery(string $sql = "")
    {
        $this->checkStatus();
        $className = self::getQueryClassName($this->driver);
        return new $className($this->pool, $this->connect, $sql);
    }

    /**
     * 创建一个查询器用于ActiveRecord操作
     *
     * @param string $className 实体类名称
     * @param bool   $isMaster  是否主节点，默认从节点
     * @param bool   $release   是否释放链接
     *
     * @return QueryBuilder
     */
    public static function getQuery(string $className, $isMaster = false, $release = true)
    {
        // 获取连接
        $pool = self::getPool($isMaster);
        $connect = $pool->getConnect();
        $driver = $connect->getDriver();

        // 驱动查询器
        $entities = Collector::$entities;
        $tableName = $entities[$className]['table']['name'];
        $queryClassName = self::getQueryClassName($driver);

        /* @var QueryBuilder $query */
        $query = new $queryClassName($pool, $connect, '', $release);
        $query->from($tableName);
        return $query;
    }

    /**
     * insert实体数据
     *
     * @param object $entity 实体
     * @param bool   $defer  是否延迟操作
     *
     * @return DataResult|bool 返回数据结果对象，成功返回插入ID，如果ID传值，插入数据库返回0，错误返回false
     */
    public function save($entity, $defer = false)
    {
        $this->checkStatus();
        $executor = $this->getExecutor();
        return $executor->save($entity, $defer);
    }

    /**
     * 按实体信息删除数据
     *
     * @param object $entity 实体
     * @param bool   $defer  是否延迟操作
     *
     * @return DataResult|bool|int 返回数据结果对象，成功返回影响行数，如果失败返回false
     */
    public function delete($entity, $defer = false)
    {
        $this->checkStatus();
        $executor = $this->getExecutor();
        return $executor->delete($entity, $defer);
    }

    /**
     * 根据ID删除数据
     *
     * @param string $className 实体类名
     * @param mixed  $id        删除ID
     * @param bool   $defer     是否延迟操作
     *
     * @return DataResult|bool|int 返回数据结果对象，成功返回影响行数，如果失败返回false
     */
    public function deleteById($className, $id, $defer = false)
    {
        $this->checkStatus();
        $executor = $this->getExecutor();
        return $executor->deleteById($className, $id, $defer);
    }

    /**
     * 根据ID删除数据
     *
     * @param string $className 实体类名
     * @param array  $ids       ID集合
     * @param bool   $defer     是否延迟操作
     *
     * @return DataResult|bool|int 返回数据结果对象，成功返回影响行数，如果失败返回false
     */
    public function deleteByIds($className, array $ids, $defer = false)
    {
        $this->checkStatus();
        $executor = $this->getExecutor();
        return $executor->deleteByIds($className, $ids, $defer);
    }

    /**
     * 按实体信息查找
     *
     * @param object $entity 实体实例
     *
     * @return QueryBuilder
     */
    public function find($entity)
    {
        $this->checkStatus();
        $executor = $this->getExecutor();
        return $executor->find($entity);
    }

    /**
     * 根据ID查找
     *
     * @param string $className 实体类名
     * @param mixed  $id        ID
     *
     * @return QueryBuilder
     */
    public function findById($className, $id)
    {
        $this->checkStatus();
        $executor = $this->getExecutor();
        return $executor->findById($className, $id);
    }

    /**
     * 根据ids查找
     *
     * @param string $className 类名
     * @param array  $ids
     *
     * @return QueryBuilder
     */
    public function findByIds($className, array $ids)
    {
        $this->checkStatus();
        $executor = $this->getExecutor();
        return $executor->findByIds($className, $ids);
    }

    /**
     * 开始事务
     */
    public function beginTransaction()
    {
        $this->checkStatus();
        $this->connect->beginTransaction();
    }

    /**
     * 回滚事务
     */
    public function rollback()
    {
        $this->checkStatus();
        $this->connect->rollback();
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $this->checkStatus();
        $this->connect->commit();
    }

    /**
     * 关闭当前实体管理器
     */
    public function close()
    {
        $this->isClose = true;
        $this->pool->release($this->connect);
    }

    /**
     * 检查当前实体管理器状态是否正取
     *
     * @throws DbException
     */
    private function checkStatus()
    {
        if ($this->isClose) {
            throw new DbException("entity manager已经关闭，不能再操作");
        }
    }

    /**
     * 获取连接池
     *
     * @param bool $isMaster 是否是主节点连接池
     *
     * @return ConnectPool
     */
    private static function getPool(bool $isMaster): ConnectPool
    {
        $dbPoolId = self::SLAVE;
        if ($isMaster) {
            $dbPoolId = self::MASTER;
        }
        /* @var DbPool $dbPool */
        $pool = App::getBean($dbPoolId);
        return $pool;
    }

    /**
     * 获取执行器
     *
     * @return Executor
     */
    private function getExecutor()
    {
        // 初始化实体执行器
        $query = self::createQuery();
        return new Executor($query);
    }

    /**
     * 获取查询器类名
     *
     * @param string $driver 驱动
     *
     * @return string
     */
    private static function getQueryClassName(string $driver)
    {
        return "Swoft\Db\\" . $driver . "\\QueryBuilder";
    }
}

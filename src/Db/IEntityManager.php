<?php

namespace Swoft\Db;

/**
 * 实体管理器接口
 *
 * @uses      IEntityManager
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IEntityManager
{
    /**
     * 实例化一个实体管理器
     *
     * @param bool $isMaster 默认从节点
     *
     * @return EntityManager
     */
    public static function create($isMaster = false);

    /**
     * 实例化一个指定ID的实体管理器
     *
     * @param string $poolId 其它数据库连接池ID
     *
     * @return EntityManager
     */
    public static function createById(string $poolId);

    /**
     * 回滚事务
     */
    public function rollback();

    /**
     * 开始事务
     */
    public function beginTransaction();

    /**
     * 提交事务
     */
    public function commit();


    /**
     * 创建一个查询器
     *
     * @param string $sql sql语句，默认为空
     *
     * @return QueryBuilder
     */
    public function createQuery(string $sql = '');
}

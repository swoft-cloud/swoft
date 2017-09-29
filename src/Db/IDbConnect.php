<?php

namespace Swoft\Db;

/**
 *
 *
 * @uses      IDbConnect
 * @version   2017年09月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IDbConnect
{
    /**
     * 执行SQL
     *
     * @param string $sql
     *
     * @return array|bool
     */
    public function execute(string $sql);

    /**
     * 开始事务
     */
    public function beginTransaction();

    /**
     * 延迟收取数据包
     *
     * @return array|bool
     */
    public function recv();

    /**
     * 获取插入ID
     *
     * @return mixed
     */
    public function getInsertId();

    /**
     * 获取更新影响的行数
     *
     * @return int
     */
    public function getAffectedRows();

    /**
     * 回滚事务
     */
    public function rollback();

    /**
     * 设置是否延迟收包
     *
     * @param bool $defer
     */
    public function setDefer($defer = true);

    /**
     * 提交事务
     */
    public function commit();
}
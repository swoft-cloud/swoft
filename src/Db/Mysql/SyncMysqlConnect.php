<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractDbConnect;

/**
 *
 *
 * @uses      SyncMysqlConnect
 * @version   2017年09月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SyncMysqlConnect extends AbstractDbConnect
{
    /**
     * @var \PDO
     */
    private $connect;



    public function createConnect()
    {
        $uri = $this->connectPool->getConnectAddress();
        $options = $this->parseUri($uri);
        $options['timeout'] = $this->connectPool->getTimeout();

        $user = $options['user'];
        $passwd = $options['password'];
        $host = $options['host'];
        $port = $options['port'];
        $dbName = $options['database'];
        $charset = $options['charset'];
        $timeout = $options['timeout'];

        $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=$charset";
        $pdo = new \PDO($dsn, $user, $passwd, array(PDO::ATTR_PERSISTENT => true));

        $this->connect = $pdo;
    }

    public function reConnect()
    {

    }

    /**
     * 执行SQL
     *
     * @param string $sql
     *
     * @return array|bool
     */
    public function execute(string $sql)
    {

    }

    /**
     * 开始事务
     */
    public function beginTransaction()
    {
        $this->connect->beginTransaction();
    }

    /**
     * 延迟收取数据包
     *
     * @return array|bool
     */
    public function recv()
    {

    }

    /**
     * 获取插入ID
     *
     * @return mixed
     */
    public function getInsertId()
    {
        $this->connect->lastInsertId();
    }

    /**
     * 获取更新影响的行数
     *
     * @return int
     */
    public function getAffectedRows()
    {
    }

    /**
     * 回滚事务
     */
    public function rollback()
    {
        $this->connect->rollBack();
    }

    /**
     * 设置是否延迟收包
     *
     * @param bool $defer
     */
    public function setDefer($defer = true)
    {
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $this->connect->rollBack();
    }
}
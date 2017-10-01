<?php

namespace Swoft\Db\Mysql;

use Swoft\App;
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

    /**
     * @var \PDOStatement
     */
    private $stmt;

    private $sql;

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

        $pdoOptions = [
            \PDO::ATTR_TIMEOUT    => $timeout,
            \PDO::ATTR_PERSISTENT => true,
        ];
        $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=$charset";
        $this->connect = new \PDO($dsn, $user, $passwd, $pdoOptions);
    }

    public function prepare(string $sql)
    {
        $this->sql = $sql . " Params:";
        $this->stmt = $this->connect->prepare($sql);
    }

    public function execute(array $params = null)
    {
        $this->bindParams($params);
        $this->formatSqlByParams($params);
        $result = $this->stmt->execute();
        App::info($this->sql);
        if ($result !== true) {
            App::error("数据库执行错误，sql=" . $this->stmt->debugDumpParams());
            return $result;
        }

        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function bindParams(array $params = null)
    {
        if (empty($params)) {
            return;
        }

        foreach ($params as $key => $value){
            $this->stmt->bindValue($key, $value);
        }
    }

    public function reConnect()
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
     * 获取插入ID
     *
     * @return mixed
     */
    public function getInsertId()
    {
        return $this->connect->lastInsertId();
    }

    /**
     * 获取更新影响的行数
     *
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->stmt->rowCount();
    }

    /**
     * 回滚事务
     */
    public function rollback()
    {
        $this->connect->rollBack();
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $this->connect->commit();
    }

    public function destory()
    {
        $this->sql = "";
        $this->stmt = null;
    }

    public function getSql()
    {
        return $this->sql;
    }

    private function formatSqlByParams($params)
    {
        if (empty($params)) {
            return;
        }
        foreach ($params as $key => $value) {
            $this->sql .= " $key=" . $value;
        }
    }
}
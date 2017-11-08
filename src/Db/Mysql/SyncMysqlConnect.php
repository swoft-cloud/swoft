<?php

namespace Swoft\Db\Mysql;

use Swoft\App;
use Swoft\Db\AbstractDbConnect;

/**
 * 同步Mysql连接
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
     * Mysql连接
     *
     * @var \PDO
     */
    private $connect;

    /**
     * 预处理
     *
     * @var \PDOStatement
     */
    private $stmt;

    /**
     * SQL语句
     *
     * @var string
     */
    private $sql;

    /**
     * 创建连接
     */
    public function createConnect()
    {
        // 配置信息初始化
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

        // 组拼$dsn串
        $pdoOptions = [
            \PDO::ATTR_TIMEOUT    => $timeout,
            \PDO::ATTR_PERSISTENT => true,
        ];
        $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=$charset";
        $this->connect = new \PDO($dsn, $user, $passwd, $pdoOptions);
    }

    /**
     * 预处理
     *
     * @param string $sql
     */
    public function prepare(string $sql)
    {
        $this->sql = $sql . " Params:";
        $this->stmt = $this->connect->prepare($sql);
    }

    /**
     * 执行SQL
     *
     * @param array|null $params
     *
     * @return array|bool
     */
    public function execute(array $params = null)
    {
        $this->bindParams($params);
        $this->formatSqlByParams($params);
        $result = $this->stmt->execute();
        if (App::isWorkerStatus()) {
            App::info($this->sql);
        }
        if ($result !== true) {
            if (App::isWorkerStatus()) {
                App::error("数据库执行错误，sql=" . $this->stmt->debugDumpParams());
            }
            return $result;
        }

        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * 绑定参数
     *
     * @param array|null $params
     */
    private function bindParams(array $params = null)
    {
        if (empty($params)) {
            return;
        }

        foreach ($params as $key => $value) {
            $this->stmt->bindValue($key, $value);
        }
    }

    /**
     * 重连接
     */
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

    /**
     * 销毁SQL
     */
    public function destory()
    {
        $this->sql = "";
        $this->stmt = null;
    }

    /**
     * SQL语句
     *
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * 格式化参数
     *
     * @param array $params
     */
    private function formatSqlByParams(array $params)
    {
        if (empty($params)) {
            return;
        }
        foreach ($params as $key => $value) {
            $this->sql .= " $key=" . $value;
        }
    }
}

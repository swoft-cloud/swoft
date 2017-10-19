<?php

namespace Swoft\Db\Mysql;

use Swoft\App;
use Swoft\Db\AbstractDbConnect;
use Swoole\Coroutine\Mysql;

/**
 * Mysql协程连接
 *
 * @uses      MysqlConnect
 * @version   2017年09月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MysqlConnect extends AbstractDbConnect
{
    /**
     * 协程Mysql连接
     *
     * @var Mysql
     */
    private $connect = null;

    /**
     * SQL语句
     *
     * @var string
     */
    private $sql = "";


    /**
     * 预处理语句
     *
     * @param string $sql
     */
    public function prepare(string $sql)
    {
        $this->sql = $sql;
    }

    /**
     * 执行语句
     *
     * @param array|null $params
     *
     * @return array|bool
     */
    public function execute(array $params = null)
    {
        $this->formatSqlByParams($params);
        $result = $this->connect->query($this->sql);
        if ($result === false) {
            App::error("mysql执行出错，connectError=" . $this->connect->connect_error . " error=" . $this->connect->error);
        }
        return $result;
    }

    /**
     * 延迟收取数据包
     *
     * @return array|bool
     */
    public function recv()
    {
        return $this->connect->recv();
    }

    /**
     * 获取插入ID
     *
     * @return mixed
     */
    public function getInsertId()
    {
        return $this->connect->insert_id;
    }

    /**
     * 获取更新影响的行数
     *
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->connect->affected_rows;
    }

    /**
     * 开始事务
     */
    public function beginTransaction()
    {
        $this->connect->query("begin;");
    }

    /**
     * 回滚事务
     */
    public function rollback()
    {
        $this->connect->query("rollback;");
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $this->connect->query("commit;");
    }

    /**
     * 设置是否延迟收包
     *
     * @param bool $defer
     */
    public function setDefer($defer = true)
    {
        $this->connect->setDefer($defer);
    }

    /**
     * 创建连接
     *
     */
    public function createConnect()
    {
        $uri = $this->connectPool->getConnectAddress();
        $options = $this->parseUri($uri);
        $options['timeout'] = $this->connectPool->getTimeout();

        // 连接mysql
        $mysql = new MySQL();
        $mysql->connect([
            'host'     => $options['host'],
            'port'     => $options['port'],
            'user'     => $options['user'],
            'password' => $options['password'],
            'database' => $options['database'],
            'timeout'  => $options['timeout'],
            'charset'  => $options['charset']
        ]);

        // 连接失败处理
        if ($mysql->connected == false) {
            throw new \InvalidArgumentException("mysql数据库连接出错，error=" . $mysql->connect_error);
        }
        $this->connect = $mysql;
    }

    /**
     * 重新连接
     */
    public function reConnect()
    {

    }

    /**
     * Sql语句
     *
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * 销毁SQL
     */
    public function destory()
    {
        $this->sql = "";
    }

    /**
     * 格式化sql参数
     *
     * @param array|null $params
     */
    private function formatSqlByParams(array $params = null)
    {
        if (empty($params)) {
            return;
        }

        // ?方式传递参数
        if (strpos($this->sql, '?') !== false) {
            $this->transferQuestionMark();
        }

        $this->sql = strtr($this->sql, $params);
    }

    /**
     * 格式化?标记
     */
    private function transferQuestionMark()
    {
        $sqlAry = explode('?', $this->sql);

        $sql = "";
        $maxBlock = count($sqlAry);
        for ($i = 0; $i < $maxBlock; $i++) {
            $n = $i + 1;
            $sql .= $sqlAry[$i];
            if ($maxBlock > $i + 1) {
                $sql .= "?" . $n . " ";
            }
        }

        $this->sql = $sql;
    }
}
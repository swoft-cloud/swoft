<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractDbConnect;
use Swoole\Coroutine\Mysql;

/**
 *
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
     * 执行SQL
     *
     * @param string $sql
     *
     * @return array|bool
     */
    public function execute(string $sql)
    {
        $result = $this->connect->query($sql);
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

    public function reConnect()
    {

    }

    /**
     * 返回数据库驱动
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->connectPool->getDriver();
    }

    private function parseUri($uri)
    {
        $parseAry = parse_url($uri);
        if (!isset($parseAry['host']) || !isset($parseAry['port']) || !isset($parseAry['path']) || !isset($parseAry['query'])) {
            throw new \InvalidArgumentException("数据量连接uri格式不正确，uri=" . $uri);
        }
        $parseAry['database'] = str_replace('/', '', $parseAry['path']);
        $query = $parseAry['query'];
        parse_str($query, $options);

        if (!isset($options['user']) || !isset($options['password'])) {
            throw new \InvalidArgumentException("数据量连接uri格式不正确，未配置用户名和密码，uri=" . $uri);
        }
        if (!isset($options['charset'])) {
            $options['charset'] = "";
        }

        $configs = array_merge($parseAry, $options);
        unset($configs['path']);
        unset($configs['query']);
        return $configs;
    }
}
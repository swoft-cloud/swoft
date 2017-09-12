<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractConnect;
use Swoole\Coroutine\Mysql;

/**
 * mysql连接
 *
 * @uses      Connect
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Connect extends AbstractConnect
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
        return $this->connect->query($sql);
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
     * @param array $options
     */
    public function createConnect(array $options)
    {
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
}
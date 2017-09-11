<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractConnect;
use Swoole\Coroutine\Mysql;

/**
 *
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
     * @var Mysql
     */
    private $connect = null;


    public function beginTransaction()
    {
        $this->connect->query("begin;");
    }

    public function commit()
    {
        $this->connect->query("commit;");
    }

    public function rollback()
    {
        $this->connect->query("rollback;");
    }

    public function setDefer($defer = true)
    {
        $this->connect->setDefer($defer);
    }

    public function recv()
    {
        return $this->connect->recv();
    }

    public function setAutoCommit(bool $autoCommit)
    {

    }

    public function createConnect($options)
    {
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

        if($mysql->connected == false){
            throw new \InvalidArgumentException("mysql数据库连接出错，error=".$mysql->connect_error);
        }
        $this->connect = $mysql;
    }


    public function execute(string $sql)
    {
        return $this->connect->query($sql);
    }
}
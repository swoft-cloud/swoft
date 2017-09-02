<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractConnect;
use Swoole\Coroutine\Mysql;

/**
 *
 *
 * @uses      MysqlConnect
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MysqlConnect extends AbstractConnect
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

    public function setAutoCommit(bool $autoCommit)
    {

    }
}
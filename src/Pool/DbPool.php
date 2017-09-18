<?php

namespace Swoft\Pool;

use Swoft\Db\Mysql\MysqlConnect;

/**
 *
 *
 * @uses      DbPool
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DbPool extends ConnectPool
{
    const MYSQL = MysqlConnect::class;

    /**
     * 数据库驱动
     *
     * @var string
     */
    private $driver = self::MYSQL;

    public function createConnect()
    {
    }

    public function reConnect($client)
    {
    }
}

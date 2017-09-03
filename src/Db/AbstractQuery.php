<?php

namespace Swoft\Db;

/**
 *
 *
 * @uses      AbstractQuery
 * @version   2017年09月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractQuery implements IQuery
{
    /**
     * 驱动连接
     *
     * @var AbstractConnect
     */
    protected $connect;

    /**
     * sql 语句
     *
     * @var string
     */
    protected $sql;


    public function __construct(AbstractConnect $connect, string $sql)
    {
        $this->connect = $connect;
        $this->sql = $sql;
    }

    public function setParameter($key, $value, $type = null)
    {

    }

    public function setParameters($parameters)
    {

    }

    public function getResult()
    {

    }

    public function getSql()
    {

    }
}
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

    protected $parameters = [];

    protected $outSql;


    public function __construct(AbstractConnect $connect, string $sql)
    {
        $this->connect = $connect;
        $this->sql = $sql;
    }

    public function setParameter($key, $value, $type = null)
    {
        if(!is_int($key)){
            $key = ":".$key;
        }

        if($type == "string" || ($type == null && is_string($value))){
            $value = '"'.$value.'"';
        }
        $this->parameters[$key] = $value;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getSql()
    {
        return $this->outSql;
    }

    abstract public function getResult(string $entityClassName = "");
}
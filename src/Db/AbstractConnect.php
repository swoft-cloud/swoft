<?php

namespace Swoft\Db;

/**
 * 抽象连接接口
 *
 * @uses      AbstractConnect
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractConnect implements IConnect
{
    /**
     * 数据库驱动比如Mysql
     *
     * @var string
     */
    private $driver;

    /**
     * AbstractConnect constructor.
     *
     * @param string $driver  驱动类型
     * @param array  $options 连接信息
     */
    public function __construct(string $driver, array $options)
    {
        $this->driver = $driver;
        $this->createConnect($options);
    }

    /**
     * 返回数据库驱动
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }
}

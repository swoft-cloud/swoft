<?php

namespace Swoft\Db\Mysql;

use Swoft\App;
use Swoft\Db\DataResult;
use Swoft\Helpers\ArrayHelper;

/**
 * Mysql查询器
 *
 * @uses      QueryBuilder
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class QueryBuilder extends \Swoft\Db\QueryBuilder
{
    /**
     * 获取执行结果
     *
     * @param string $className 数据填充到实体的类名
     *
     * @return array|bool 返回结果如果执行失败返回false，更新成功返回true,查询返回数据
     */
    public function getResult(string $className = "")
    {
        // 如果没有组合SQL
        if (empty($this->lastSql)) {
            $this->getSql();
        }

        // sqlId用于记录日志
        $sqlId = md5($this->lastSql);
        $profileKey = 'mysql.' . $sqlId;
        App::profileStart($profileKey);

        // 执行SQL
        $result = $this->connect->execute($this->lastSql);

        App::profileEnd($profileKey);
        App::debug("SQL语句执行结果 sqlId=$sqlId result=" . json_encode($result) . "sql=" . $this->lastSql);

        // 如果是数组，填充实体处理
        if (is_array($result) && !empty($className)) {
            $result = ArrayHelper::resultToEntity($result, $className);
        }

        // 是否释放连接，类ActiveRecord操作需要释放连接
        if ($this->release) {
            $this->pool->release($this->connect);
        }
        return $result;
    }

    /**
     * 返回数据结果对象
     *
     * @param string $className 数据填充到实体的类名
     *
     * @return DataResult 返回数据结果对象
     */
    public function getDefer(string $className = "")
    {
        // 如果没有组合SQL
        if (empty($this->lastSql)) {
            $this->getSql();
        }

        // sqlId用于记录日志
        $sqlId = md5($this->lastSql);
        $profileKey = "mysql." . $sqlId;

        // 执行SQL
        $this->connect->setDefer();
        $result = $this->connect->execute($this->lastSql);

        App::debug("SQL语句执行(defer) sqlId=$sqlId sql=" . $this->lastSql);
        return new DataResult($this->pool, $this->connect, $profileKey, $result, $this->release);
    }
}

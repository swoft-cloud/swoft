<?php

namespace Swoft\Db\Mysql;

use Swoft\App;
use Swoft\Db\DataResult;
use Swoft\Helper\ArrayHelper;

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
        $sql = $this->getStatement();

        // sqlId用于记录日志
        $sqlId = md5($sql);
        $profileKey = 'mysql.' . $sqlId;
        App::profileStart($profileKey);

        // 执行SQL
        $this->connect->prepare($sql);
        $result = $this->connect->execute($this->parameters);

        App::profileEnd($profileKey);
        App::debug("SQL语句执行结果 sqlId=$sqlId result=" . json_encode($result) . "sql=" . $sql);

        // 插入成功返回插入ID,更新或删除成功，返回影响行数,查询一条数据，返回一维数组
        $result = $this->transferResult($result);

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
     * @return DataResult 返回数据结果对象
     */
    public function getDefer()
    {
        $sql = $this->getStatement();

        // sqlId用于记录日志
        $sqlId = md5($sql);
        $profileKey = "mysql." . $sqlId;

        // 执行SQL
        $this->connect->setDefer();
        $this->connect->prepare($sql);
        $result = $this->connect->execute($this->parameters);
        App::debug("SQL语句执行(defer) sqlId=$sqlId sql=" . $sql);

        $isUpdateOrDelete = $this->isDelete() || $this->isUpdate();
        $isFindOne = $this->isSelect() && isset($this->limit['limit']) && $this->limit['limit'] == 1;
        $dataResult = new DataResult($this->pool, $this->connect, $profileKey, $result, $this->release);

        // 结果转换参数
        $dataResult->setIsInsert($this->isInsert());
        $dataResult->setIsUpdateOrDelete($isUpdateOrDelete);
        $dataResult->setIsFindOne($isFindOne);

        return $dataResult;
    }

    /**
     * 转换结果
     *
     * @param mixed $result 查询结果
     *
     * @return mixed
     */
    private function transferResult($result)
    {
        $isFindOne = isset($this->limit['limit']) && $this->limit['limit'] == 1;
        $isUpdateOrDelete = $this->isDelete() || $this->isUpdate();
        if ($this->isInsert() && $result !== false) {
            $result = $this->connect->getInsertId();
        } elseif ($isUpdateOrDelete && $result !== false) {
            $result = $this->connect->getAffectedRows();
        } elseif ($this->isSelect() && $result !== false && $isFindOne) {
            $result = $result[0]?? [];
        }
        return $result;
    }

    /**
     * @param mixed $key
     *
     * @return string
     */
    protected function formatParamsKey($key): string
    {
        if(is_string($key)){
            return ":" . $key;
        }
        if(App::isWorkerStatus()){
            return "?" . $key;
        }

        return $key;
    }

}

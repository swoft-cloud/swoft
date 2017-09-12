<?php

namespace Swoft\Db\Mysql;

use Swoft\App;
use Swoft\Db\DbResult;
use Swoft\Helpers\ArrayHelper;

/**
 *
 *
 * @uses      QueryBuilder
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class QueryBuilder extends \Swoft\Db\QueryBuilder
{
    public function getResult(string $className = "")
    {
        if(empty($this->lastSql)){
            $this->getSql();
        }

        $sqlId = md5($this->lastSql);
        $profileKey = 'mysql.'.$sqlId;
        App::profileStart($profileKey);
        $result = $this->connect->execute($this->lastSql);
        App::profileEnd($profileKey);
        App::debug("SQL语句执行结果 sqlId=$sqlId result=".json_encode($result)."sql=".$this->lastSql);
        if(is_array($result)){
            $result = ArrayHelper::resultToEntity($result, $className);
        }
        if($this->release){
            $this->pool->release($this->connect);
        }
        return $result;
    }

    /**
     * @param string $className
     *
     * @return DbResult
     */
    public function getDefer(string $className = "")
    {
        if(empty($this->lastSql)){
            $this->getSql();
        }

        $sqlId = md5($this->lastSql);
        $profileKey = "mysql.".$sqlId;
        $this->connect->setDefer();
        $result = $this->connect->execute($this->lastSql);
        App::debug("SQL语句执行(defer) sqlId=$sqlId sql=".$this->lastSql);

        return new DbResult($this->pool, $this->connect, $profileKey, $result, $this->release);
    }
}
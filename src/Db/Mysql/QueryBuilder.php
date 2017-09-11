<?php

namespace Swoft\Db\Mysql;

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
        if(empty($this->sql)){
            $this->getSql();
        }
        $this->sql = strtr($this->sql, $this->parameters);
        $result = $this->connect->execute($this->sql);
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
        $profileKey = "mysql.query";
        $sql = $this->getStatement();
        $this->sql = strtr($sql, $this->parameters);

        $this->connect->setDefer();
        $result = $this->connect->execute($this->sql);

        return new DbResult($this->pool, $this->connect, $profileKey, $result, $this->release);
    }
}
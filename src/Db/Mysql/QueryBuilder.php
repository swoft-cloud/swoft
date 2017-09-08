<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractQueryBuilder;
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
class QueryBuilder extends AbstractQueryBuilder
{
    public function getResult(string $className = "")
    {
        $sql = $this->getStatement();
        $this->sql = strtr($sql, $this->parameters);
        $result = $this->connect->execute($this->sql);
        $result = $this->getEntityResult($result, $className);

        return $result;
    }

    public function getDeferResult(string $className = "")
    {

    }

    protected function getEntityResult(array $result, $className)
    {
        if(empty($className) ||  !is_array($result)){
            return $result;
        }
        $entities = [];
        foreach ($result as $entityData){
            if(!is_array($entityData)){
                continue;
            }
            $entities[] = ArrayHelper::arrayToEntity($entityData, $className);
        }
        return $entities;
    }
}
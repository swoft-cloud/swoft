<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractQuery;
use Swoft\Helpers\ArrayHelper;

/**
 *
 *
 * @uses      Query
 * @version   2017年09月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Query extends AbstractQuery
{
    public function getResult(string $entityClassName = "")
    {
        $this->outSql = strtr($this->sql, $this->parameters);
        $result = $this->connect->execute($this->outSql);
        $result = $this->getEntityResult($result, $entityClassName);

        return $result;
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
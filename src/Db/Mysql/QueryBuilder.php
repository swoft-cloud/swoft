<?php

namespace Swoft\Db\Mysql;

use Swoft\Db\AbstractQueryBuilder;

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
    public function getResult()
    {
        $statement = $this->getStatement();
        return $statement;
    }
    public function getDeferResult()
    {

    }
}
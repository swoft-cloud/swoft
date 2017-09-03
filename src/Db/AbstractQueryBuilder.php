<?php

namespace Swoft\Db;

/**
 *
 *
 * @uses      AbstractQueryBuilder
 * @version   2017年09月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractQueryBuilder implements IQueryBuilder
{


    public function __construct()
    {
        
    }

    public function getQuery()
    {
        // TODO: Implement getQuery() method.
    }
}
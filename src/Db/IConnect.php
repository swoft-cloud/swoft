<?php

namespace Swoft\Db;

/**
 *
 *
 * @uses      IConnect
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IConnect
{
    public function beginTransaction();

    public function commit();

    public function rollback();
}

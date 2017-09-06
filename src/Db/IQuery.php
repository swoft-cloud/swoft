<?php

namespace Swoft\Db;

/**
 *
 *
 * @uses      IQuery
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IQuery
{
    public function setParameter($key, $value, $type = null);
    public function setParameters(array $parameters);
    public function getResult(string $entityClassName = "");
    public function getSql();
}
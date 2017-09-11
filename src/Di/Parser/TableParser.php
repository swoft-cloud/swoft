<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Table;
use Swoft\Di\Collector;

/**
 *
 *
 * @uses      TableParser
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class TableParser extends AbstractParser
{

    /**
     * @param string $className
     * @param Table $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $tableName = $objectAnnotation->getName();
        Collector::$entities[$className]['table']['name'] = $tableName;
        return $this->defaultClass;
    }
}
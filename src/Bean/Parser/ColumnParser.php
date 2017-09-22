<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Column;
use Swoft\Bean\Collector;

/**
 * Column注解解析
 *
 * @uses      ColumnParser
 * @version   2017年09月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ColumnParser extends AbstractParser
{
    /**
     * Column注解解析
     *
     * @param string      $className
     * @param Column      $objectAnnotation
     * @param string      $propertyName
     * @param string      $methodName
     * @param string|null $propertyValue
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $columnName = $objectAnnotation->getName();

        // 表映射收集
        $entity =[
            'type' => $objectAnnotation->getType(),
            'length' => $objectAnnotation->getLength(),
            'column' => $columnName,
            'default' => $propertyValue
        ];
        Collector::$entities[$className]['field'][$propertyName]= $entity;
        Collector::$entities[$className]['column'][$columnName] = $propertyName;
        return null;
    }
}
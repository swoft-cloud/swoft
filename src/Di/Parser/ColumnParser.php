<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Column;

/**
 *
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
     * @param string $className
     * @param Column $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "")
    {
        $columnName = $objectAnnotation->getName();
        $this->resourceDataProxy->entities[$className]['field'][$propertyName]['type'] = $objectAnnotation->getType();
        $this->resourceDataProxy->entities[$className]['field'][$propertyName]['length'] = $objectAnnotation->getLength();
        $this->resourceDataProxy->entities[$className]['field'][$propertyName]['column'] = $columnName;
        $this->resourceDataProxy->entities[$className]['column'][$columnName] = $propertyName;
        return null;
    }
}
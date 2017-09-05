<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Table;

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
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "")
    {
        if(!isset($this->resourceDataProxy->entities[$className])){
            return $this->defaultClass;
        }

        $tableName = $objectAnnotation->getName();
        $this->resourceDataProxy->entities[$className]['tableName'] = $tableName;
        return $this->defaultClass;
    }
}
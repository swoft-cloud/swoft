<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Mapping;
use Swoft\Bean\Collector;

/**
 * Mapping注解解析
 *
 * @uses      MappingParser
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MappingParser extends AbstractParser
{
    /**
     * Mapping注解解析解析
     *
     * @param string  $className
     * @param Mapping $objectAnnotation
     * @param string  $propertyName
     * @param string  $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        if (!isset(Collector::$serviceMapping[$className])) {
            return;
        }

        // 路由收集
        $mapped = $objectAnnotation->getName();
        Collector::$serviceMapping[$className]['routes'][] = [
            'mappedName' => $mapped,
            'methodName' => $methodName
        ];
    }
}
<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\RequestMethod;
use Swoft\Bean\Collector;

/**
 * 方法没有注解解析器
 *
 * @uses      MethodWithoutAnnotationParser
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MethodWithoutAnnotationParser extends AbstractParser
{
    /**
     * 方法没有注解解析
     *
     * @param string $className
     * @param null   $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        if (!isset(Collector::$requestMapping[$className])) {
            return;
        }

        // 路由收集
        Collector::$requestMapping[$className]['routes'][] = [
            'route'  => "",
            'method' => [RequestMethod::GET, RequestMethod::POST],
            'action' => $methodName
        ];
        return null;
    }
}
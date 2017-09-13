<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\RequestMapping;
use Swoft\Di\Collector;

/**
 * RequestMapping注解解析器
 *
 * @uses      RequestMappingParser
 * @version   2017年09月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestMappingParser extends AbstractParser
{

    /**
     * RequestMapping注解解析
     *
     * @param string         $className
     * @param RequestMapping $objectAnnotation
     * @param string         $propertyName
     * @param string         $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        if (!isset(Collector::$requestMapping[$className])) {
            return;
        }

        // 路由收集
        $route = $objectAnnotation->getRoute();
        $httpMethod = $objectAnnotation->getMethod();
        Collector::$requestMapping[$className]['routes'][] = [
            'route'  => $route,
            'method' => $httpMethod,
            'action' => $methodName
        ];
    }
}
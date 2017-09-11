<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\RequestMethod;
use Swoft\Di\Collector;

/**
 *
 *
 * @uses      MethodWithoutAnnotationParser
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MethodWithoutAnnotationParser extends AbstractParser
{
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        if (!isset(Collector::$requestMapping[$className])) {
            return;
        }

        Collector::$requestMapping[$className]['routes'][] = [
            'route'  => "",
            'method' => [RequestMethod::GET, RequestMethod::POST],
            'action' => $methodName
        ];
    }
}
<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\RequestMethod;

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
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "")
    {
        if (!isset($this->resourceDataProxy->requestMapping[$className])) {
            return;
        }

        $this->resourceDataProxy->requestMapping[$className]['routes'][] = [
            'route'  => "",
            'method' => [RequestMethod::GET, RequestMethod::POST],
            'action' => $methodName
        ];
    }
}
<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\AutoProcess;
use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\Collector;

/**
 * 进程注解
 *
 * @uses      AutoProcessParser
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AutoProcessParser extends AbstractParser
{
    /**
     * task注解解析
     *
     * @param string      $className
     * @param AutoProcess $objectAnnotation
     * @param string      $propertyName
     * @param string      $methodName
     *
     * @return array
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $name = $objectAnnotation->getName();
        $beanName = empty($name) ? $className : $name;
        Collector::$processses[$beanName] = [
            'inout' => $objectAnnotation->isInout(),
            'pipe'  => $objectAnnotation->isPipe(),
        ];
        return [$beanName, Scope::SINGLETON];
    }
}
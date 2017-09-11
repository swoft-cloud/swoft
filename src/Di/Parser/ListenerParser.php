<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Listener;
use Swoft\Di\Annotation\Scope;
use Swoft\Di\Collector;

/**
 *
 *
 * @uses      ListenerParser
 * @version   2017年09月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ListenerParser extends AbstractParser
{
    /**
     * @param string   $className
     * @param Listener $objectAnnotation
     * @param string   $propertyName
     * @param string   $methodName
     *
     * @return array
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $beanName = $className;
        $scope = Scope::SINGLETON;
        $eventName = $objectAnnotation->getEvent();
        Collector::$listeners[$eventName][] = $beanName;
        return [$beanName, $scope];
    }
}
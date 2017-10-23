<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\Annotation\Service;
use Swoft\Bean\Collector;

/**
 * Service注解
 *
 * @uses      ServiceParser
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServiceParser extends AbstractParser
{
    /**
     * Service注解解析
     *
     * @param string  $className
     * @param Service $objectAnnotation
     * @param string  $propertyName
     * @param string  $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $beanName = $className;
        $scope = Scope::SINGLETON;

        // service映射收集
        $serverName = $objectAnnotation->getName();
        Collector::$serviceMapping[$className]['name'] = $serverName;

        return [$beanName, $scope];
    }
}
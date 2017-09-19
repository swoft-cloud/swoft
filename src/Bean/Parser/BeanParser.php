<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Bean;

/**
 * Bean注解解析器
 *
 * @uses      BeanParser
 * @version   2017年09月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeanParser extends AbstractParser
{
    /**
     * Bean注解解析
     *
     * @param string $className
     * @param Bean   $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     *
     * @return array
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $name = $objectAnnotation->getName();
        $scope = $objectAnnotation->getScope();
        $beanName = empty($name) ? $className : $name;
        return [$beanName, $scope];
    }
}
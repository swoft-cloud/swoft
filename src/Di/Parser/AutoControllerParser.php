<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\AutoController;
use Swoft\Di\Annotation\Scope;

/**
 *
 *
 * @uses      AutoControllerParser
 * @version   2017年09月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AutoControllerParser extends AbstractParser
{
    /**
     * @param string         $className
     * @param AutoController $objectAnnotation
     * @param string         $propertyName
     * @param string         $methodName
     * @param string|null         $propertyValue
     *
     * @return array
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $beanName = $className;
        $scope = Scope::SINGLETON;
        $prefix = $objectAnnotation->getPrefix();
        $this->resourceDataProxy->requestMapping[$className]['prefix'] = $prefix;
        return [$beanName, $scope];
    }
}
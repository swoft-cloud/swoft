<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Required;
use Swoft\Di\Collector;

/**
 *
 *
 * @uses      RequiredParser
 * @version   2017年09月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequiredParser extends AbstractParser
{
    /**
     * @param string $className
     * @param Required $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        Collector::$entities[$className]['field'][$propertyName]['required'] = true;
        return null;
    }
}
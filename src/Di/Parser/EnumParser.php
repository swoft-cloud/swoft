<?php

namespace Swoft\Di\Parser;

use Swoft\Di\Annotation\Enum;
use Swoft\Di\Collector;

/**
 *
 *
 * @uses      EnumParser
 * @version   2017年09月12日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class EnumParser extends AbstractParser
{

    /**
     * @param string|null $className
     * @param Enum      $objectAnnotation
     * @param string      $propertyName
     * @param string      $methodName
     * @param string|null $propertyValue
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "", $propertyValue = null)
    {
        $annotationClass = get_class($objectAnnotation);
        $validator = basename(str_replace("\\", "/", $annotationClass));

        $validatorAry = [
            'name' => $validator,
            'value' => [$objectAnnotation->getValue()]
        ];
        Collector::$entities[$className]['field'][$propertyName]['validates'][] = $validatorAry;
        return null;
    }
}
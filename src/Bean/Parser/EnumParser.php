<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Enum;
use Swoft\Bean\Collector;

/**
 * Enum注解解析器
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
     * Enum注解解析
     *
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

        // 表映射收集
        $validatorAry = [
            'name' => $validator,
            'value' => [$objectAnnotation->getValue()]
        ];
        Collector::$entities[$className]['field'][$propertyName]['validates'][] = $validatorAry;
        return null;
    }
}
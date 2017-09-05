<?php

namespace Swoft\Di\Parser;

/**
 *
 *
 * @uses      EntityParser
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class EntityParser extends AbstractParser
{
    public function parser(string $className, $objectAnnotation = null, string $propertyName = "", string $methodName = "")
    {
        $this->resourceDataProxy->entities[$className] = [];
        return $this->defaultClass;
    }
}
<?php

namespace Swoft\Di\Wrapper;

use Swoft\Di\Annotation\Column;
use Swoft\Di\Annotation\Entity;
use Swoft\Di\Annotation\Id;
use Swoft\Di\Annotation\Required;
use Swoft\Di\Annotation\Table;

/**
 *
 *
 * @uses      EntityWrapper
 * @version   2017年09月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class EntityWrapper extends AbstractWrapper
{
    protected $classAnnotations
        = [
            Entity::class,
            Table::class,
        ];

    protected $propertyAnnotations
        = [
            Id::class,
            Column::class,
            Required::class,
        ];

    public function isParseClassAnnotations($annotations)
    {
        return isset($annotations[Entity::class]);
    }

    public function isParsePropertyAnnotations($annotations)
    {
        return isset($annotations[Column::class]);
    }

    public function isParseMethodAnnotations($annotations)
    {
        return false;
    }
}
<?php

namespace Swoft\Di\Wrapper;

use Swoft\Di\Annotation\Column;
use Swoft\Di\Annotation\Entity;
use Swoft\Di\Annotation\Enum;
use Swoft\Di\Annotation\Id;
use Swoft\Di\Annotation\Required;
use Swoft\Di\Annotation\Table;

/**
 * 实体封装器
 *
 * @uses      EntityWrapper
 * @version   2017年09月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class EntityWrapper extends AbstractWrapper
{
    /**
     * 类注解
     *
     * @var array
     */
    protected $classAnnotations
        = [
            Entity::class,
            Table::class,
        ];

    /**
     * 属性注解
     *
     * @var array
     */
    protected $propertyAnnotations
        = [
            Id::class,
            Enum::class,
            Column::class,
            Required::class,
        ];

    /**
     * 是否解析类注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations)
    {
        return isset($annotations[Entity::class]);
    }

    /**
     * 是否解析属性注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations)
    {
        return isset($annotations[Column::class]);
    }

    /**
     * 是否解析方法注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations)
    {
        return false;
    }
}
<?php

namespace Swoft\Bean\ObjectDefinition;

/**
 * 属性注入对象
 *
 * @uses      PropertyInjection
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class PropertyInjection
{
    /**
     * 属性名称
     *
     * @var string
     */
    private $propertyName;

    /**
     * 属性值
     *
     * @var mixed
     */
    private $value;

    /**
     * 是否是bean引用
     *
     * @var bool
     */
    private $ref = false;

    /**
     * PropertyInjection constructor.
     *
     * @param string $propertyName
     * @param mixed  $value
     * @param bool   $ref
     */
    public function __construct(string $propertyName, $value, $ref = false)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
        $this->ref = $ref;
    }

    /**
     * 获取属性名称
     *
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * 获取属性值
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * 获取属性是否是bean引用
     *
     * @return bool
     */
    public function isRef(): bool
    {
        return $this->ref;
    }
}

<?php

namespace Swoft\Bean\ObjectDefinition;

/**
 * 数组属性的参数或构造函数的参数注入对象
 *
 * @uses      ArgsInjection
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ArgsInjection
{
    /**
     * 参数值
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
     * ArgsInjection constructor.
     *
     * @param mixed $value
     * @param bool  $ref
     */
    public function __construct($value, $ref = false)
    {
        $this->value = $value;
        $this->ref = $ref;
    }

    /**
     * 参数值
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * 参数是否是bean引用
     *
     * @return bool
     */
    public function isRef(): bool
    {
        return $this->ref;
    }
}

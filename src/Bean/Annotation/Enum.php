<?php

namespace Swoft\Bean\Annotation;

/**
 * 枚举类型注解
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @uses      Enum
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Enum
{
    /**
     * 枚举值集合
     *
     * @var array
     */
    private $value = [];

    /**
     * Enum constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->value = $values['value'];
        }
        if (isset($values['value'])) {
            $this->value = $values['value'];
        }
    }

    /**
     * 枚举值
     *
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }
}
<?php

namespace Swoft\Di\Annotation;

/**
 * 枚举注解
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
     * @var array
     */
    private $value = [];

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
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }
}
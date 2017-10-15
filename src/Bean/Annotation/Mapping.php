<?php

namespace Swoft\Bean\Annotation;

/**
 * Mapping注解
 *
 * @Annotation
 * @Target({"METHOD"})
 *
 * @uses      Mapping
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Mapping
{
    /**
     * 映射的名称，默认函数名称
     *
     * @var string
     */
    private $name = "";

    /**
     * Mapping constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
<?php

namespace Swoft\Di\Annotation;

/**
 * 表列注解
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @uses      Column
 * @version   2017年08月31日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Column
{
    /**
     * 名称
     *
     * @var string
     */
    private $name;

    /**
     * 类型
     *
     * @var string
     */
    private $type = "string";

    /**
     * 长度
     *
     * @var int
     */
    private $length = -1;


    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['type'])) {
            $this->type = $values['type'];
        }
        if (isset($values['length'])) {
            $this->length = $values['length'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }
}
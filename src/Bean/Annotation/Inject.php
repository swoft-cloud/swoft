<?php

namespace Swoft\Bean\Annotation;

/**
 * inject注解
 *
 * @Annotation
 * @Target({"PROPERTY","METHOD"})
 *
 * @uses      Inject
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Inject
{
    /**
     * 注入bean名称
     *
     * @var string
     */
    private $name = "";

    /**
     * Inject constructor.
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
     * 获取bean名称
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

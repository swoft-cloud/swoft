<?php

namespace Swoft\Bean\Annotation;

/**
 * task注解
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @uses      Task
 * @version   2017年09月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Task
{
    /**
     * 任务名称
     *
     * @var string
     */
    private $name = "";

    /**
     * Bean constructor.
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
     * 获取任务名称
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
<?php

namespace Swoft\Bean\Annotation;

/**
 * 自动创建进程注解
 *
 * @Annotation
 * @Target("CLASS")
 * @uses      AutoProcess
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AutoProcess
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $inout = false;

    /**
     * @var bool
     */
    private $pipe = true;

    /**
     * AutoController constructor.
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
        if (isset($values['inout'])) {
            $this->inout = $values['inout'];
        }
        if (isset($values['pipe'])) {
            $this->pipe = $values['pipe'];
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
     * @return bool
     */
    public function isInout(): bool
    {
        return $this->inout;
    }

    /**
     * @return bool
     */
    public function isPipe(): bool
    {
        return $this->pipe;
    }
}
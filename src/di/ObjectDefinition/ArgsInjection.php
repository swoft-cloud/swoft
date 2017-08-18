<?php

namespace swoft\di\ObjectDefinition;

/**
 *
 *
 * @uses      ArgsInjection
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ArgsInjection
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private $ref = false;


    public function __construct($value, $ref = false)
    {
        $this->value = $value;
        $this->ref = $ref;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isRef(): bool
    {
        return $this->ref;
    }
}
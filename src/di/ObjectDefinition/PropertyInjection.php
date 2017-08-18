<?php
namespace swoft\di\ObjectDefinition;

/**
 *
 * @uses      PropertyInjection
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class PropertyInjection
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private $ref = false;

    public function __construct($propertyName, $value, $ref = false)
    {
        $this->propertyName = $propertyName;
        $this->value = $ref;
        $this->ref = $ref;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
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
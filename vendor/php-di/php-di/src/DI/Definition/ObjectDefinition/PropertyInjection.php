<?php

namespace DI\Definition\ObjectDefinition;

/**
 * Describe an injection in a class property.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PropertyInjection
{
    /**
     * Property name.
     * @var string
     */
    private $propertyName;

    /**
     * Value that should be injected in the property.
     * @var mixed
     */
    private $value;

    /**
     * Use for injecting in properties of parent classes: the class name
     * must be the name of the parent class because private properties
     * can be attached to the parent classes, not the one we are resolving.
     * @var string|null
     */
    private $className;

    /**
     * @param string      $propertyName Property name
     * @param mixed       $value        Value that should be injected in the property
     * @param string|null $className
     */
    public function __construct($propertyName, $value, $className = null)
    {
        $this->propertyName = (string) $propertyName;
        $this->value = $value;
        $this->className = $className;
    }

    /**
     * @return string Property name
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return mixed Value that should be injected in the property
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        return $this->className;
    }
}

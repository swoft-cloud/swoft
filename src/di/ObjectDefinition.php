<?php

namespace swoft\di;

use swoft\di\annotation\Scope;
use swoft\di\ObjectDefinition\MethodInjection;

/**
 *
 *
 * @uses      ObjectDefinition
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ObjectDefinition
{
    /**
     * Entry name (most of the time, same as $classname).
     * @var string
     */
    private $name;

    /**
     * Class name (if null, then the class name is $name).
     * @var string|null
     */
    private $className;

    /**
     * @var int
     */
    private $scope = Scope::SINGLETON;

    /**
     * Constructor parameter injection.
     * @var MethodInjection
     */
    private $constructorInjection = null;

    /**
     * Property injections.
     */
    private $propertyInjections = [];

    /**
     * Method calls.
     * @var MethodInjection[][]
     */
    private $methodInjections = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param null|string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return int
     */
    public function getScope(): int
    {
        return $this->scope;
    }

    /**
     * @param int $scope
     */
    public function setScope(int $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return MethodInjection
     */
    public function getConstructorInjection()
    {
        return $this->constructorInjection;
    }

    /**
     * @param MethodInjection $constructorInjection
     */
    public function setConstructorInjection(MethodInjection $constructorInjection)
    {
        $this->constructorInjection = $constructorInjection;
    }

    /**
     * @return mixed
     */
    public function getPropertyInjections()
    {
        return $this->propertyInjections;
    }

    /**
     * @param mixed $propertyInjections
     */
    public function setPropertyInjections($propertyInjections)
    {
        $this->propertyInjections = $propertyInjections;
    }

    /**
     * @return ObjectDefinition\MethodInjection[][]
     */
    public function getMethodInjections(): array
    {
        return $this->methodInjections;
    }

    /**
     * @param ObjectDefinition\MethodInjection[][] $methodInjections
     */
    public function setMethodInjections(array $methodInjections)
    {
        $this->methodInjections = $methodInjections;
    }
}
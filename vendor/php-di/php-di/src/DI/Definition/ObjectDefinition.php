<?php

namespace DI\Definition;

use DI\Definition\Dumper\ObjectDefinitionDumper;
use DI\Definition\ObjectDefinition\MethodInjection;
use DI\Definition\ObjectDefinition\PropertyInjection;
use DI\Scope;
use ReflectionClass;

/**
 * Defines how an object can be instantiated.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ObjectDefinition implements Definition, CacheableDefinition, HasSubDefinition
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
     * Constructor parameter injection.
     * @var MethodInjection|null
     */
    private $constructorInjection;

    /**
     * Property injections.
     * @var PropertyInjection[]
     */
    private $propertyInjections = [];

    /**
     * Method calls.
     * @var MethodInjection[][]
     */
    private $methodInjections = [];

    /**
     * @var string|null
     */
    private $scope;

    /**
     * @var bool|null
     */
    private $lazy;

    /**
     * Store if the class exists. Storing it (in cache) avoids recomputing this.
     *
     * @var bool
     */
    private $classExists;

    /**
     * Store if the class is instantiable. Storing it (in cache) avoids recomputing this.
     *
     * @var bool
     */
    private $isInstantiable;

    /**
     * @param string $name Class name
     * @param string $className
     */
    public function __construct($name, $className = null)
    {
        $this->name = (string) $name;
        $this->setClassName($className);
    }

    /**
     * @return string Entry name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $className
     */
    public function setClassName($className)
    {
        $this->className = $className;

        $this->updateCache();
    }

    /**
     * @return string Class name
     */
    public function getClassName()
    {
        if ($this->className !== null) {
            return $this->className;
        }

        return $this->name;
    }

    /**
     * @return MethodInjection|null
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
     * @return PropertyInjection[] Property injections
     */
    public function getPropertyInjections()
    {
        return $this->propertyInjections;
    }

    public function addPropertyInjection(PropertyInjection $propertyInjection)
    {
        $className = $propertyInjection->getClassName();
        if ($className) {
            // Index with the class name to avoid collisions between parent and
            // child private properties with the same name
            $key = $className . '::' . $propertyInjection->getPropertyName();
        } else {
            $key = $propertyInjection->getPropertyName();
        }

        $this->propertyInjections[$key] = $propertyInjection;
    }

    /**
     * @return MethodInjection[] Method injections
     */
    public function getMethodInjections()
    {
        // Return array leafs
        $injections = [];
        array_walk_recursive($this->methodInjections, function ($injection) use (&$injections) {
            $injections[] = $injection;
        });

        return $injections;
    }

    /**
     * @param MethodInjection $methodInjection
     */
    public function addMethodInjection(MethodInjection $methodInjection)
    {
        $method = $methodInjection->getMethodName();
        if (! isset($this->methodInjections[$method])) {
            $this->methodInjections[$method] = [];
        }
        $this->methodInjections[$method][] = $methodInjection;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function getScope()
    {
        return $this->scope ?: Scope::SINGLETON;
    }

    /**
     * @param bool|null $lazy
     */
    public function setLazy($lazy)
    {
        $this->lazy = $lazy;
    }

    /**
     * @return bool
     */
    public function isLazy()
    {
        if ($this->lazy !== null) {
            return $this->lazy;
        } else {
            // Default value
            return false;
        }
    }

    /**
     * @return bool
     */
    public function classExists()
    {
        return $this->classExists;
    }

    /**
     * @return bool
     */
    public function isInstantiable()
    {
        return $this->isInstantiable;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubDefinitionName()
    {
        return $this->getClassName();
    }

    /**
     * {@inheritdoc}
     */
    public function setSubDefinition(Definition $definition)
    {
        if (! $definition instanceof self) {
            return;
        }

        // The current prevails
        if ($this->className === null) {
            $this->setClassName($definition->className);
        }
        if ($this->scope === null) {
            $this->scope = $definition->scope;
        }
        if ($this->lazy === null) {
            $this->lazy = $definition->lazy;
        }

        // Merge constructor injection
        $this->mergeConstructorInjection($definition);

        // Merge property injections
        $this->mergePropertyInjections($definition);

        // Merge method injections
        $this->mergeMethodInjections($definition);
    }

    public function __toString()
    {
        return (new ObjectDefinitionDumper)->dump($this);
    }

    private function mergeConstructorInjection(ObjectDefinition $definition)
    {
        if ($definition->getConstructorInjection() !== null) {
            if ($this->constructorInjection !== null) {
                // Merge
                $this->constructorInjection->merge($definition->getConstructorInjection());
            } else {
                // Set
                $this->constructorInjection = $definition->getConstructorInjection();
            }
        }
    }

    private function mergePropertyInjections(ObjectDefinition $definition)
    {
        foreach ($definition->propertyInjections as $propertyName => $propertyInjection) {
            if (! isset($this->propertyInjections[$propertyName])) {
                // Add
                $this->propertyInjections[$propertyName] = $propertyInjection;
            }
        }
    }

    private function mergeMethodInjections(ObjectDefinition $definition)
    {
        foreach ($definition->methodInjections as $methodName => $calls) {
            if (array_key_exists($methodName, $this->methodInjections)) {
                $this->mergeMethodCalls($calls, $methodName);
            } else {
                // Add
                $this->methodInjections[$methodName] = $calls;
            }
        }
    }

    private function mergeMethodCalls(array $calls, $methodName)
    {
        foreach ($calls as $index => $methodInjection) {
            // Merge
            if (array_key_exists($index, $this->methodInjections[$methodName])) {
                // Merge
                $this->methodInjections[$methodName][$index]->merge($methodInjection);
            } else {
                // Add
                $this->methodInjections[$methodName][$index] = $methodInjection;
            }
        }
    }

    private function updateCache()
    {
        $className = $this->getClassName();

        $this->classExists = class_exists($className) || interface_exists($className);

        if (! $this->classExists) {
            $this->isInstantiable = false;

            return;
        }

        $class = new ReflectionClass($className);
        $this->isInstantiable = $class->isInstantiable();
    }
}

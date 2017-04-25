<?php

namespace DI\Definition\Helper;

use DI\Definition\Exception\DefinitionException;
use DI\Definition\ObjectDefinition;
use DI\Definition\ObjectDefinition\MethodInjection;
use DI\Definition\ObjectDefinition\PropertyInjection;

/**
 * Helps defining how to create an instance of a class.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ObjectDefinitionHelper implements DefinitionHelper
{
    /**
     * @var string|null
     */
    private $className;

    /**
     * @var bool|null
     */
    private $lazy;

    /**
     * @var string|null
     */
    private $scope;

    /**
     * Array of constructor parameters.
     * @var array
     */
    private $constructor = [];

    /**
     * Array of properties and their value.
     * @var array
     */
    private $properties = [];

    /**
     * Array of methods and their parameters.
     * @var array
     */
    private $methods = [];

    /**
     * Helper for defining an object.
     *
     * @param string|null $className Class name of the object.
     *                               If null, the name of the entry (in the container) will be used as class name.
     */
    public function __construct($className = null)
    {
        $this->className = $className;
    }

    /**
     * Define the entry as lazy.
     *
     * A lazy entry is created only when it is used, a proxy is injected instead.
     *
     * @return ObjectDefinitionHelper
     */
    public function lazy()
    {
        $this->lazy = true;

        return $this;
    }

    /**
     * Defines the scope of the entry.
     *
     * @param string $scope
     *
     * @return ObjectDefinitionHelper
     */
    public function scope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Defines the arguments to use to call the constructor.
     *
     * This method takes a variable number of arguments, example:
     *     ->constructor($param1, $param2, $param3)
     *
     * @param mixed ... Parameters to use for calling the constructor of the class.
     *
     * @return ObjectDefinitionHelper
     */
    public function constructor()
    {
        $this->constructor = func_get_args();

        return $this;
    }

    /**
     * Defines a value for a specific argument of the constructor.
     *
     * This method is usually used together with annotations or autowiring, when a parameter
     * is not (or cannot be) type-hinted. Using this method instead of constructor() allows to
     * avoid defining all the parameters (letting them being resolved using annotations or autowiring)
     * and only define one.
     *
     * @param string $parameter Parameter for which the value will be given.
     * @param mixed  $value     Value to give to this parameter.
     *
     * @return ObjectDefinitionHelper
     */
    public function constructorParameter($parameter, $value)
    {
        $this->constructor[$parameter] = $value;

        return $this;
    }

    /**
     * Defines a value to inject in a property of the object.
     *
     * @param string $property Entry in which to inject the value.
     * @param mixed  $value    Value to inject in the property.
     *
     * @return ObjectDefinitionHelper
     */
    public function property($property, $value)
    {
        $this->properties[$property] = $value;

        return $this;
    }

    /**
     * Defines a method to call and the arguments to use.
     *
     * This method takes a variable number of arguments after the method name, example:
     *
     *     ->method('myMethod', $param1, $param2)
     *
     * Can be used multiple times to declare multiple calls.
     *
     * @param string $method Name of the method to call.
     * @param mixed  ...     Parameters to use for calling the method.
     *
     * @return ObjectDefinitionHelper
     */
    public function method($method)
    {
        $args = func_get_args();
        array_shift($args);

        if (! isset($this->methods[$method])) {
            $this->methods[$method] = [];
        }

        $this->methods[$method][] = $args;

        return $this;
    }

    /**
     * Defines a method to call and a value for a specific argument.
     *
     * This method is usually used together with annotations or autowiring, when a parameter
     * is not (or cannot be) type-hinted. Using this method instead of method() allows to
     * avoid defining all the parameters (letting them being resolved using annotations or
     * autowiring) and only define one.
     *
     * If multiple calls to the method have been configured already (e.g. in a previous definition)
     * then this method only overrides the parameter for the *first* call.
     *
     * @param string $method    Name of the method to call.
     * @param string $parameter Name or index of the parameter for which the value will be given.
     * @param mixed  $value     Value to give to this parameter.
     *
     * @return ObjectDefinitionHelper
     */
    public function methodParameter($method, $parameter, $value)
    {
        // Special case for the constructor
        if ($method === '__construct') {
            $this->constructor[$parameter] = $value;

            return $this;
        }

        if (! isset($this->methods[$method])) {
            $this->methods[$method] = [0 => []];
        }

        $this->methods[$method][0][$parameter] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition($entryName)
    {
        $definition = new ObjectDefinition($entryName, $this->className);

        if ($this->lazy !== null) {
            $definition->setLazy($this->lazy);
        }
        if ($this->scope !== null) {
            $definition->setScope($this->scope);
        }

        if (! empty($this->constructor)) {
            $parameters = $this->fixParameters($definition, '__construct', $this->constructor);
            $constructorInjection = MethodInjection::constructor($parameters);
            $definition->setConstructorInjection($constructorInjection);
        }

        if (! empty($this->properties)) {
            foreach ($this->properties as $property => $value) {
                $definition->addPropertyInjection(
                    new PropertyInjection($property, $value)
                );
            }
        }

        if (! empty($this->methods)) {
            foreach ($this->methods as $method => $calls) {
                foreach ($calls as $parameters) {
                    $parameters = $this->fixParameters($definition, $method, $parameters);
                    $methodInjection = new MethodInjection($method, $parameters);
                    $definition->addMethodInjection($methodInjection);
                }
            }
        }

        return $definition;
    }

    /**
     * Fixes parameters indexed by the parameter name -> reindex by position.
     *
     * This is necessary so that merging definitions between sources is possible.
     *
     * @param ObjectDefinition $definition
     * @param string          $method
     * @param array           $parameters
     * @throws DefinitionException
     * @return array
     */
    private function fixParameters(ObjectDefinition $definition, $method, $parameters)
    {
        $fixedParameters = [];

        foreach ($parameters as $index => $parameter) {
            // Parameter indexed by the parameter name, we reindex it with its position
            if (is_string($index)) {
                $callable = [$definition->getClassName(), $method];
                try {
                    $reflectionParameter = new \ReflectionParameter($callable, $index);
                } catch (\ReflectionException $e) {
                    throw DefinitionException::create($definition, sprintf("Parameter with name '%s' could not be found. %s.", $index, $e->getMessage()));
                }

                $index = $reflectionParameter->getPosition();
            }

            $fixedParameters[$index] = $parameter;
        }

        return $fixedParameters;
    }
}

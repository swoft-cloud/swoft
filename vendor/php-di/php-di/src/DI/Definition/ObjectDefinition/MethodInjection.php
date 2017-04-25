<?php

namespace DI\Definition\ObjectDefinition;

use DI\Definition\Definition;
use DI\Scope;

/**
 * Describe an injection in an object method.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MethodInjection implements Definition
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param string $methodName
     * @param array  $parameters
     */
    public function __construct($methodName, array $parameters = [])
    {
        $this->methodName = (string) $methodName;
        $this->parameters = $parameters;
    }

    public static function constructor(array $parameters = [])
    {
        return new self('__construct', $parameters);
    }

    /**
     * @return string Method name
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Replace the parameters of the definition by a new array of parameters.
     *
     * @param array $parameters
     */
    public function replaceParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function merge(MethodInjection $definition)
    {
        // In case of conflicts, the current definition prevails.
        $this->parameters = $this->parameters + $definition->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getScope()
    {
        return Scope::PROTOTYPE;
    }
}

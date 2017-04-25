<?php

namespace DI\Definition\Helper;

use DI\Definition\DecoratorDefinition;
use DI\Definition\FactoryDefinition;

/**
 * Helps defining how to create an instance of a class using a factory (callable).
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FactoryDefinitionHelper implements DefinitionHelper
{
    /**
     * @var callable
     */
    private $factory;

    /**
     * @var string|null
     */
    private $scope;

    /**
     * @var bool
     */
    private $decorate;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param callable $factory
     * @param bool     $decorate Is the factory decorating a previous definition?
     */
    public function __construct($factory, $decorate = false)
    {
        $this->factory = $factory;
        $this->decorate = $decorate;
    }

    /**
     * Defines the scope of the entry.
     *
     * @param string $scope
     *
     * @return FactoryDefinitionHelper
     */
    public function scope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @param string $entryName Container entry name
     * @return FactoryDefinition
     */
    public function getDefinition($entryName)
    {
        if ($this->decorate) {
            return new DecoratorDefinition($entryName, $this->factory, $this->scope, $this->parameters);
        }

        return new FactoryDefinition($entryName, $this->factory, $this->scope, $this->parameters);
    }

    /**
     * Defines arguments to pass to the factory.
     *
     * Because factory methods do not yet support annotations or autowiring, this method
     * should be used to define all parameters except the ContainerInterface and RequestedEntry.
     *
     * Multiple calls can be made to the method to override individual values.
     *
     * @param string $parameter Name or index of the parameter for which the value will be given.
     * @param mixed  $value     Value to give to this parameter.
     *
     * @return FactoryDefinitionHelper
     */
    public function parameter($parameter, $value)
    {
        $this->parameters[$parameter] = $value;

        return $this;
    }
}

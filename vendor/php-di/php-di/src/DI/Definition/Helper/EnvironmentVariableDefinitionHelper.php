<?php

namespace DI\Definition\Helper;

use DI\Definition\EnvironmentVariableDefinition;

/**
 * Helps defining how to create an instance of an environment variable definition.
 *
 * @author James Harris <james.harris@icecave.com.au>
 */
class EnvironmentVariableDefinitionHelper implements DefinitionHelper
{
    /**
     * The name of the environment variable.
     * @var string
     */
    private $variableName;

    /**
     * Whether or not the environment variable definition is optional.
     *
     * If true and the environment variable given by $variableName has not been
     * defined, $defaultValue is used.
     *
     * @var bool
     */
    private $isOptional;

    /**
     * The default value to use if the environment variable is optional and not provided.
     * @var mixed
     */
    private $defaultValue;

    /**
     * @param string  $variableName The name of the environment variable
     * @param bool $isOptional   Whether or not the environment variable definition is optional
     * @param mixed   $defaultValue The default value to use if the environment variable is optional and not provided
     */
    public function __construct($variableName, $isOptional, $defaultValue = null)
    {
        $this->variableName = $variableName;
        $this->isOptional = $isOptional;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @param string $entryName Container entry name
     *
     * @return EnvironmentVariableDefinition
     */
    public function getDefinition($entryName)
    {
        return new EnvironmentVariableDefinition($entryName, $this->variableName, $this->isOptional, $this->defaultValue);
    }
}

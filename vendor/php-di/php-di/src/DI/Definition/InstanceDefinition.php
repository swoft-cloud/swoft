<?php

namespace DI\Definition;

use DI\Scope;

/**
 * Defines injections on an existing class instance.
 *
 * @since  5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InstanceDefinition implements Definition
{
    /**
     * Instance on which to inject dependencies.
     *
     * @var object
     */
    private $instance;

    /**
     * @var ObjectDefinition
     */
    private $objectDefinition;

    /**
     * @param object          $instance
     * @param ObjectDefinition $objectDefinition
     */
    public function __construct($instance, ObjectDefinition $objectDefinition)
    {
        $this->instance = $instance;
        $this->objectDefinition = $objectDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // Name are superfluous for instance definitions
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getScope()
    {
        return Scope::PROTOTYPE;
    }

    /**
     * @return object
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @return ObjectDefinition
     */
    public function getObjectDefinition()
    {
        return $this->objectDefinition;
    }
}

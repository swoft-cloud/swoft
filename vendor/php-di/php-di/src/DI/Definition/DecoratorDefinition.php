<?php

namespace DI\Definition;

/**
 * Factory that decorates a sub-definition.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DecoratorDefinition extends FactoryDefinition implements Definition, HasSubDefinition
{
    /**
     * @var Definition
     */
    private $decorated;

    /**
     * @return string
     */
    public function getSubDefinitionName()
    {
        return $this->getName();
    }

    /**
     * @param Definition $definition
     */
    public function setSubDefinition(Definition $definition)
    {
        $this->decorated = $definition;
    }

    /**
     * @return Definition
     */
    public function getDecoratedDefinition()
    {
        return $this->decorated;
    }

    public function __toString()
    {
        return 'Decorate(' . $this->getSubDefinitionName() . ')';
    }
}

<?php

namespace DI\Definition;

use DI\Definition\Exception\DefinitionException;

/**
 * Extends an array definition by adding new elements into it.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ArrayDefinitionExtension extends ArrayDefinition implements HasSubDefinition
{
    /**
     * @var ArrayDefinition
     */
    private $subDefinition;

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        if (! $this->subDefinition) {
            return parent::getValues();
        }

        return array_merge($this->subDefinition->getValues(), parent::getValues());
    }

    /**
     * @return string
     */
    public function getSubDefinitionName()
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setSubDefinition(Definition $definition)
    {
        if (! $definition instanceof ArrayDefinition) {
            throw new DefinitionException(sprintf(
                'Definition %s tries to add array entries but the previous definition is not an array',
                $this->getName()
            ));
        }

        $this->subDefinition = $definition;
    }
}

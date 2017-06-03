<?php

namespace DI\Definition;

/**
 * A definition that has a sub-definition.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface HasSubDefinition extends Definition
{
    /**
     * @return string
     */
    public function getSubDefinitionName();

    /**
     * @param Definition $definition
     */
    public function setSubDefinition(Definition $definition);
}

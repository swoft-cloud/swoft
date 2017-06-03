<?php

namespace DI\Definition\Resolver;

use DI\Definition\ArrayDefinition;
use DI\Definition\Definition;
use DI\Definition\Helper\DefinitionHelper;
use DI\DependencyException;
use Exception;

/**
 * Resolves an array definition to a value.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ArrayResolver implements DefinitionResolver
{
    /**
     * @var DefinitionResolver
     */
    private $definitionResolver;

    /**
     * @param DefinitionResolver $definitionResolver Used to resolve nested definitions.
     */
    public function __construct(DefinitionResolver $definitionResolver)
    {
        $this->definitionResolver = $definitionResolver;
    }

    /**
     * Resolve an array definition to a value.
     *
     * An array definition can contain simple values or references to other entries.
     *
     * @param ArrayDefinition $definition
     *
     * {@inheritdoc}
     */
    public function resolve(Definition $definition, array $parameters = [])
    {
        $values = $definition->getValues();

        // Resolve nested definitions
        foreach ($values as $key => $value) {
            if ($value instanceof DefinitionHelper) {
                $values[$key] = $this->resolveDefinition($value, $definition, $key);
            }
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function isResolvable(Definition $definition, array $parameters = [])
    {
        return true;
    }

    private function resolveDefinition(DefinitionHelper $value, ArrayDefinition $definition, $key)
    {
        try {
            return $this->definitionResolver->resolve($value->getDefinition(''));
        } catch (DependencyException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new DependencyException(sprintf(
                'Error while resolving %s[%s]. %s',
                $definition->getName(),
                $key,
                $e->getMessage()
            ), 0, $e);
        }
    }
}

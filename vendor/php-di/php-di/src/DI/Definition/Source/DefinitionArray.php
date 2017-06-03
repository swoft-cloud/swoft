<?php

namespace DI\Definition\Source;

use DI\Definition\ArrayDefinition;
use DI\Definition\Definition;
use DI\Definition\FactoryDefinition;
use DI\Definition\Helper\DefinitionHelper;
use DI\Definition\ObjectDefinition;
use DI\Definition\ValueDefinition;

/**
 * Reads DI definitions from a PHP array.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DefinitionArray implements DefinitionSource, MutableDefinitionSource
{
    const WILDCARD = '*';
    /**
     * Matches anything except "\".
     */
    const WILDCARD_PATTERN = '([^\\\\]+)';

    /**
     * DI definitions in a PHP array.
     * @var array
     */
    private $definitions = [];

    /**
     * Cache of wildcard definitions.
     * @var array
     */
    private $wildcardDefinitions;

    /**
     * @param array $definitions
     */
    public function __construct(array $definitions = [])
    {
        $this->definitions = $definitions;
    }

    /**
     * @param array $definitions DI definitions in a PHP array indexed by the definition name.
     */
    public function addDefinitions(array $definitions)
    {
        // The newly added data prevails
        // "for keys that exist in both arrays, the elements from the left-hand array will be used"
        $this->definitions = $definitions + $this->definitions;

        // Clear cache
        $this->wildcardDefinitions = null;
    }

    /**
     * {@inheritdoc}
     */
    public function addDefinition(Definition $definition)
    {
        $this->definitions[$definition->getName()] = $definition;

        // Clear cache
        $this->wildcardDefinitions = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition($name)
    {
        // Look for the definition by name
        if (array_key_exists($name, $this->definitions)) {
            return $this->castDefinition($this->definitions[$name], $name);
        }

        // Build the cache of wildcard definitions
        if ($this->wildcardDefinitions === null) {
            $this->wildcardDefinitions = [];
            foreach ($this->definitions as $key => $definition) {
                if (strpos($key, self::WILDCARD) !== false) {
                    $this->wildcardDefinitions[$key] = $definition;
                }
            }
        }

        // Look in wildcards definitions
        foreach ($this->wildcardDefinitions as $key => $definition) {
            // Turn the pattern into a regex
            $key = preg_quote($key);
            $key = '#' . str_replace('\\' . self::WILDCARD, self::WILDCARD_PATTERN, $key) . '#';
            if (preg_match($key, $name, $matches) === 1) {
                $definition = $this->castDefinition($definition, $name);

                // For a class definition, we replace * in the class name with the matches
                // *Interface -> *Impl => FooInterface -> FooImpl
                if ($definition instanceof ObjectDefinition) {
                    array_shift($matches);
                    $definition->setClassName(
                        $this->replaceWildcards($definition->getClassName(), $matches)
                    );
                }

                return $definition;
            }
        }

        return null;
    }

    /**
     * @param mixed  $definition
     * @param string $name
     * @return Definition
     */
    private function castDefinition($definition, $name)
    {
        if ($definition instanceof DefinitionHelper) {
            $definition = $definition->getDefinition($name);
        } elseif (is_array($definition)) {
            $definition = new ArrayDefinition($name, $definition);
        } elseif ($definition instanceof \Closure) {
            $definition = new FactoryDefinition($name, $definition);
        } elseif (! $definition instanceof Definition) {
            $definition = new ValueDefinition($name, $definition);
        }

        return $definition;
    }

    /**
     * Replaces all the wildcards in the string with the given replacements.
     * @param string   $string
     * @param string[] $replacements
     * @return string
     */
    private function replaceWildcards($string, array $replacements)
    {
        foreach ($replacements as $replacement) {
            $pos = strpos($string, self::WILDCARD);
            if ($pos !== false) {
                $string = substr_replace($string, $replacement, $pos, 1);
            }
        }

        return $string;
    }
}

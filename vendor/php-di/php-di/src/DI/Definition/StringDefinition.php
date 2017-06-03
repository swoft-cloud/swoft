<?php

namespace DI\Definition;

use DI\DependencyException;
use DI\Scope;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Definition of a string composed of other strings.
 *
 * @since 5.0
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class StringDefinition implements Definition, SelfResolvingDefinition
{
    /**
     * Entry name.
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $expression;

    /**
     * @param string $name       Entry name
     * @param string $expression
     */
    public function __construct($name, $expression)
    {
        $this->name = $name;
        $this->expression = $expression;
    }

    /**
     * @return string Entry name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getScope()
    {
        return Scope::SINGLETON;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    public function resolve(ContainerInterface $container)
    {
        $expression = $this->expression;

        $result = preg_replace_callback('#\{([^\{\}]+)\}#', function (array $matches) use ($container) {
            try {
                return $container->get($matches[1]);
            } catch (NotFoundExceptionInterface $e) {
                throw new DependencyException(sprintf(
                    "Error while parsing string expression for entry '%s': %s",
                    $this->getName(),
                    $e->getMessage()
                ), 0, $e);
            }
        }, $expression);

        if ($result === null) {
            throw new \RuntimeException(sprintf('An unknown error occurred while parsing the string definition: \'%s\'', $expression));
        }

        return $result;
    }

    public function isResolvable(ContainerInterface $container)
    {
        return true;
    }

    public function __toString()
    {
        return $this->expression;
    }
}

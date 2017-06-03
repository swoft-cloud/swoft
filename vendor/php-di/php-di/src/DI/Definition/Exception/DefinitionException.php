<?php

namespace DI\Definition\Exception;

use DI\Definition\Definition;

/**
 * Invalid DI definitions.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DefinitionException extends \Exception
{
    public static function create(Definition $definition, $message)
    {
        return new self(sprintf(
            '%s' . PHP_EOL . 'Full definition:' . PHP_EOL . '%s',
            $message,
            (string) $definition
        ));
    }
}

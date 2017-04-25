<?php

namespace DI\Definition\Source;

use DI\Definition\Exception\DefinitionException;

/**
 * Reads DI definitions from a file returning a PHP array.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DefinitionFile extends DefinitionArray
{
    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * File containing definitions, or null if the definitions are given as a PHP array.
     * @var string|null
     */
    private $file;

    /**
     * @param string $file File in which the definitions are returned as an array.
     */
    public function __construct($file)
    {
        // Lazy-loading to improve performances
        $this->file = $file;

        parent::__construct([]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition($name)
    {
        $this->initialize();

        return parent::getDefinition($name);
    }

    /**
     * Lazy-loading of the definitions.
     * @throws DefinitionException
     */
    private function initialize()
    {
        if ($this->initialized === true) {
            return;
        }

        $definitions = require $this->file;

        if (! is_array($definitions)) {
            throw new DefinitionException("File {$this->file} should return an array of definitions");
        }

        $this->addDefinitions($definitions);

        $this->initialized = true;
    }
}

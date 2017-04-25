<?php

namespace DI\Annotation;

use DI\Scope;
use UnexpectedValueException;

/**
 * "Injectable" annotation.
 *
 * Marks a class as injectable
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @author Domenic Muskulus <domenic@muskulus.eu>
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
final class Injectable
{
    /**
     * The scope of an class: prototype, singleton.
     * @var string|null
     */
    private $scope;

    /**
     * Should the object be lazy-loaded.
     * @var bool|null
     */
    private $lazy;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['scope'])) {
            if ($values['scope'] === 'prototype') {
                $this->scope = Scope::PROTOTYPE;
            } elseif ($values['scope'] === 'singleton') {
                $this->scope = Scope::SINGLETON;
            } else {
                throw new UnexpectedValueException(sprintf("Value '%s' is not a valid scope", $values['scope']));
            }
        }
        if (isset($values['lazy'])) {
            $this->lazy = (bool) $values['lazy'];
        }
    }

    /**
     * @return string|null
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return bool|null
     */
    public function isLazy()
    {
        return $this->lazy;
    }
}

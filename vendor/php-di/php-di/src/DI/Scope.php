<?php

namespace DI;

/**
 * Scope enum.
 *
 * The scope defines the lifecycle of an entry.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Scope
{
    /**
     * A singleton entry will be computed once and shared.
     *
     * For a class, only a single instance of the class will be created.
     */
    const SINGLETON = 'singleton';

    /**
     * A prototype entry will be recomputed each time it is asked.
     *
     * For a class, this will create a new instance each time.
     */
    const PROTOTYPE = 'prototype';

    /**
     * Method kept for backward compatibility, use the constant instead.
     *
     * @return string
     */
    public static function SINGLETON()
    {
        return self::SINGLETON;
    }

    /**
     * Method kept for backward compatibility, use the constant instead.
     *
     * @return string
     */
    public static function PROTOTYPE()
    {
        return self::PROTOTYPE;
    }
}

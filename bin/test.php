<?php

abstract class Test
{
    private static $ab = 1;

    /**
     * @return int
     */
    public static function getAb(): int
    {
        return self::$ab;
    }

}

echo Test::getAb();
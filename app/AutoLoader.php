<?php

namespace App;

use Swoft\Annotation\LoaderInterface;

/**
 * Class AutoLoader
 */
class AutoLoader implements LoaderInterface
{
    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }
}
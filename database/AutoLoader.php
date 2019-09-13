<?php declare(strict_types=1);

namespace Database;

use Swoft;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();

        Swoft::setAlias('@database', dirname(__DIR__) . '/database');
    }

    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }
}

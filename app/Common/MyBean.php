<?php declare(strict_types=1);

namespace App\Common;

use Swoft\Bean\Annotation\Mapping\Bean;
use function vdump;

/**
 * Class MyBean
 *
 * @package App\Common
 * @Bean()
 */
class MyBean
{
    public function myMethod(): array
    {
        vdump(__METHOD__);

        return ['hi'];
    }
}

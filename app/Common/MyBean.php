<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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

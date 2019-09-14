<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class RequestBeanTwo
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST)
 */
class RequestBeanTwo
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return ['data'];
    }
}

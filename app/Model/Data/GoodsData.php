<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Model\Data;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class GoodsData
 *
 * @package App\Model\Data
 * @Bean()
 */
class GoodsData
{
    public function getConfig(): array
    {
        return \config('app.warehouseCode', []);
    }
}

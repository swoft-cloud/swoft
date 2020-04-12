<?php declare(strict_types=1);

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

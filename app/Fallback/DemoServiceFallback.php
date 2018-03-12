<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Fallback;

use App\Lib\DemoInterface;
use Swoft\Sg\Bean\Annotation\Fallback;
use Swoft\Core\ResultInterface;

/**
 * Fallback demo
 *
 * @Fallback("demoFallback")
 * @method ResultInterface deferGetUsers(array $ids)
 * @method ResultInterface deferGetUser(string $id)
 * @method ResultInterface deferGetUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
 */
class DemoServiceFallback implements DemoInterface
{
    public function getUsers(array $ids)
    {
        return ['fallback', 'getUsers', func_get_args()];
    }

    public function getUser(string $id)
    {
        return ['fallback', 'getUser', func_get_args()];
    }

    public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = 'desc')
    {
        return ['fallback', 'getUserByCond', func_get_args()];
    }
}
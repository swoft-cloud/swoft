<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Lib;

use Swoft\Core\ResultInterface;

/**
 * The interface of demo service
 *
 * @method ResultInterface deferGetUsers(array $ids)
 * @method ResultInterface deferGetUser(string $id)
 * @method ResultInterface deferGetUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
 */
interface DemoInterface
{
    /**
     * @param array $ids
     *
     * @return array
     *
     * <pre>
     * [
     *    'uid' => [],
     *    'uid2' => [],
     *    ......
     * ]
     * <pre>
     */
    public function getUsers(array $ids);

    /**
     * @param string $id
     *
     * @return array
     */
    public function getUser(string $id);

    public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = 'desc');
}
<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc");
}
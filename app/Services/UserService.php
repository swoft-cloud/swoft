<?php

namespace App\Services;

use App\Models\Logic\UserLogic;
use Swoft\Bean\Annotation\Enum;
use Swoft\Bean\Annotation\Floats;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Mapping;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\Service;
use Swoft\Bean\Annotation\Strings;

/**
 * 用户service
 *
 * @Service()
 * @uses      UserService
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserService
{
    /**
     * 逻辑层
     *
     * @Inject()
     * @var UserLogic
     */
    private $userLogic;

    /**
     * 用户信息
     *
     * @Mapping("getUserInfo")
     * @param array ...$uids
     *
     * @return array
     */
    public function getUserInfo(...$uids)
    {
        return $this->userLogic->getUserInfo($uids);
    }

    /**
     * @Mapping("getUser")
     * @Enum(name="type", values={1,2,3})
     * @Number(name="uid", min=1, max=10)
     * @Strings(name="name", min=2, max=5)
     * @Floats(name="price", min=1.2, max=1.9)
     *
     * @param int    $type
     * @param int    $uid
     * @param string $name
     * @param float  $price
     * @param string $desc  default value
     * @return array
     */
    public function getUserByCond(int $type, int $uid, string $name, float $price, string $desc = "desc")
    {
        return [$type, $uid, $name, $price, $desc];
    }

    /**
     * 未使用注解，默认方法名
     *
     * @return array
     */
    public function getUserList()
    {
        return ['uid1', 'uid2'];
    }
}
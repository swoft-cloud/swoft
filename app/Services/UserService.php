<?php

namespace App\Services;

use App\Models\Logic\UserLogic;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Mapping;
use Swoft\Bean\Annotation\Service;

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
     * 未使用注解，默认方法名
     *
     * @return array
     */
    public function getUserList()
    {
        return ['uid1', 'uid2'];
    }
}
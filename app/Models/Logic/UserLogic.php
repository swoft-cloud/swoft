<?php

namespace App\Models\Logic;

use Swoft\Bean\Annotation\Bean;

/**
 * 用户逻辑层
 * 同时可以被controller server task使用
 *
 * @Bean()
 * @uses      UserLogic
 * @version   2017年10月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserLogic
{
    public function getUserInfo(array $uids)
    {
        $user = [
            'name' => 'boby',
            'desc' => 'this is boby'
        ];

        $data = [];
        foreach ($uids as $uid) {
            $user['uid'] = $uid;
            $data[] = $user;
        }

        return $data;
    }
}
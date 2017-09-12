<?php
namespace App\Controllers\Services;

use Swoft\Di\Annotation\Bean;
use Swoft\Web\InnerService;

/**
 * RPC服务函数
 *
 * @Bean()
 * @uses      UserService
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserService extends InnerService
{
    public function getUserInfo(...$uids)
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

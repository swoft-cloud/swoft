<?php

namespace app\models\dao;

use swoft\di\annotation\Bean;

/**
 *
 * @Bean()
 * @uses      UserDao
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserDao
{
    public function getUserInfo()
    {
        return [
            'uid' => 666,
            'name' => 'stelin'
        ];
    }
}
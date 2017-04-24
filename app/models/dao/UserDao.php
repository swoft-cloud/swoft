<?php

namespace app\models\dao;

/**
 *
 *
 * @uses      UserDao
 * @version   2017年04月25日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 北京尤果网文化传媒有限公司
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
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
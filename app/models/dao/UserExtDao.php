<?php

namespace app\models\dao;

/**
 *
 *
 * @uses      UserExtDao
 * @version   2017年04月25日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class UserExtDao
{
    public function getExtInfo()
    {
        return [
            'age' => 18,
            'desc' => 'hello',
            'address' => 'chengdu'
        ];
    }
}
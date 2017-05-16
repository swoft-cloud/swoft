<?php

namespace app\models\data;

use app\models\dao\UserDao;
use app\models\dao\UserExtDao;

/**
 *
 *
 * @uses      UserExtData
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserExtData
{
    /**
     * @Inject
     * @var UserExtDao
     */
    private $userExtDao;

    /**
     * @Inject
     * @var UserData
     */
    private $userData;

    public function getExtInfo()
    {
        return $this->userExtDao->getExtInfo();
    }
}
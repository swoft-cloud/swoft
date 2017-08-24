<?php

namespace app\models\data;

use app\models\dao\UserDao;
use swoft\di\annotation\Bean;
use swoft\di\annotation\Inject;

/**
 *
 * @Bean()
 * @uses      UserData
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserData
{
    /**
     *
     * @Inject()
     * @var UserDao
     */
    private $userDao;

    public function getUserInfo()
    {
        return $this->userDao->getUserInfo();
    }
}
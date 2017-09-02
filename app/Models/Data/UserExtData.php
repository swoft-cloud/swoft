<?php

namespace App\Models\Data;

use App\Models\Dao\UserDao;
use App\Models\Dao\UserExtDao;
use Swoft\Di\Annotation\Bean;
use Swoft\Di\Annotation\Inject;

/**
 *
 * @Bean()
 * @uses      UserExtData
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserExtData
{
    /**
     * @Inject()
     * @var UserExtDao
     */
    private $userExtDao;

    /**
     * @Inject()
     * @var UserData
     */
    private $userData;

    public function getExtInfo()
    {
        return $this->userExtDao->getExtInfo();
    }
}
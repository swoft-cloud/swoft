<?php

namespace app\models\logic;

use app\models\data\UserData;
use app\models\data\UserExtData;

/**
 *
 *
 * @uses      IndexLogic
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class IndexLogic
{
    /**
     *
     * @Inject
     * @var UserData
     */
    private $userData;

    /**
     *
     * @Inject
     * @var UserExtData
     */
    private $userExtData;

    public function getUser()
    {
        $base = $this->userData->getUserInfo();
        $ext = $this->userExtData->getExtInfo();

        return array_merge($base, $ext);
    }


}
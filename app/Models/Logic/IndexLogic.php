<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Models\Logic;

use App\Models\Data\UserData;
use App\Models\Data\UserExtData;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;

/**
 *
 * @Bean()
 * @uses      IndexLogic
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class IndexLogic
{
    /**
     *
     * @Inject()
     * @var UserData
     */
    private $userData;

    /**
     *
     * @Inject()
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

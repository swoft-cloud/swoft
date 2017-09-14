<?php

namespace App\Controllers;

use App\Models\Entity\User;
use Swoft\Bean\Annotation\AutoController;
use Swoft\Web\Controller;

/**
 * orm使用demo
 *
 * @AutoController()
 * @uses      OrmController
 * @version   2017年09月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class OrmController extends Controller
{
    /**
     * AR save操作
     */
    public function actionArSave()
    {
        $user = new User();
        //        $user->setId(120);
        $user->setName("stelin");
        $user->setSex(1);
        $user->setDesc("this my desc");
        $user->setAge(mt_rand(1, 100));
        $result = $user->save();

        $user->setDesc("this is defer desc");
        $dataResult = $user->save(true);
        $deferResult = $dataResult->getResult();

        $this->outputJson([$result, $deferResult]);
    }
}
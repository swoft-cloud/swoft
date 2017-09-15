<?php

namespace App\Controllers;

use App\Models\Entity\Count;
use App\Models\Entity\User;
use Swoft\Bean\Annotation\AutoController;
use Swoft\Db\QueryBuilder;
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
//        $user = new User();
//        //        $user->setId(120);
//        $user->setName("stelin");
//        $user->setSex(1);
//        $user->setDesc("this my desc");
//        $user->setAge(mt_rand(1, 100));
//        $result = $user->save();
//
//        $user->setDesc("this is defer desc");
//        $dataResult = $user->save(true);
//        $deferResult = $dataResult->getResult();
//
//        $this->outputJson([$result, $deferResult]);

        $count = new Count();
        $count->setUid(346);
        $count->setFans(mt_rand(1,1000));
        $count->setFollows(mt_rand(1,1000));

        $this->outputJson($count->save());
    }

    /**
     * 实体内容删除
     */
    public function actionArDelete()
    {
        $user = new User();
        //        $user->setId(286);
        $user->setAge(126);

        //        $result = $user->delete();
        $defer = $user->delete(true);

        $this->outputJson($defer->getResult());
    }

    /**
     * 删除ID测试
     */
    public function actionArDeleteId()
    {
//        $result = User::deleteById(284);
        $result = User::deleteById(287, true);

        $this->outputJson($result->getResult());
    }

    /**
     * 删除IDs测试
     */
    public function actionArDeleteIds()
    {
//        $result = User::deleteByIds([291, 292]);
        $result = User::deleteByIds([288, 289], true);

        $this->outputJson($result->getResult());
    }

    /**
     * 更新操作
     */
    public function actionArUpdate()
    {
        $query = User::findById(285);

        /* @var User $user */
        $user = $query->getResult(User::class);
        $user->setName("upateNameUser2");
        $user->setSex(0);

        $result = $user->update();
        //        $result = $user->update(true);
        //        $result = $result->getResult();

        $this->outputJson([$result]);
    }

    /**
     * 实体查找
     */
    public function actionArFind()
    {
        $user = new User();
        $user->setSex(1);
        $user->setAge(93);
        $query = $user->find();
        //        $result = $query->getResult();

        /* @var User $userResult */
        //        $userResult = $query->getResult(User::class);

        $defer = $query->getDefer();
        //        $result = $defer->getResult();

        $result = $defer->getResult(User::class);
        $ql = $query->getSql();
        var_dump($result);
        $this->outputJson([$ql, $result]);
    }

    /**
     * Ar ID查找
     */
    public function actionArFindId()
    {
        $query = User::findById(236);
        $result = $query->getResult();

        /* @var User $userObject */
        $userObject = $query->getResult(User::class);

        $query = User::findById(238);
        //        $deferResult = $query->getDefer()->getResult();

        /* @var User $deferResult */
        $deferResult = $query->getDefer()->getResult(User::class);

        $this->outputJson([$result, $userObject->getName(), $deferResult->getName()]);
    }

    /**
     * Ar IDS查找
     */
    public function actionArFindIds()
    {
        $query = User::findByIds([285, 286]);

        $sql = $query->getSql();

        //        $defer = $query->getDefer();
        //        $result = $defer->getResult(User::class);

        $result = $query->getResult();

        $this->outputJson([$result, $sql]);
    }

    /**
     * Ar Query
     */
    public function actionArQuery()
    {
//        $query = User::query()->select('*')->andWhere('sex', 1)->orderBy('id',QueryBuilder::ORDER_BY_DESC)->limit(3);
//        $query = User::query()->selects(['id', 'sex' => 'sex2'])->andWhere('sex', 1)->orderBy('id',QueryBuilder::ORDER_BY_DESC)->limit(3);
        $query = User::query()->selects(['id', 'sex' => 'sex2'])
            ->leftJoin('count', 'count.uid=user.id')
            ->andWhere('id', 346)
            ->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)
            ->limit(2);
//        $result = $query->getResult();
        $defer = $query->getDefer();
        $result = $defer->getResult();
        $this->outputJson([$result, $query->getSql()]);
    }
}
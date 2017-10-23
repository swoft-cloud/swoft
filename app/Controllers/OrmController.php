<?php

namespace App\Controllers;

use App\Models\Entity\Count;
use App\Models\Entity\User;
use Swoft\Bean\Annotation\AutoController;
use Swoft\Db\EntityManager;
use Swoft\Db\QueryBuilder;
use Swoft\Db\Types;
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
        $count->setFans(mt_rand(1, 1000));
        $count->setFollows(mt_rand(1, 1000));

        $this->outputJson($count->save());
    }

    /**
     * EM查找
     */
    public function actionSave()
    {
        $user = new User();
        $user->setName("stelin");
        $user->setSex(1);
        $user->setDesc("this my desc");
        $user->setAge(mt_rand(1, 100));

        $em = EntityManager::create();
        //        $result = $em->save($user);
        $defer = $em->save($user, true);
        $result = $defer->getResult();
        $em->close();

        $this->outputJson([$result]);
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
     * Em 删除
     */
    public function actionDelete()
    {
        $user = new User();
        $user->setId(418);

        $em = EntityManager::create();
        //        $result = $em->delete($user);
        $result = $em->delete($user, true);
        $em->close();

        $this->outputJson([$result->getResult()]);
    }

    /**
     * EM deleteId
     */
    public function actionDeleteId()
    {
        $em = EntityManager::create();
        //        $result = $em->deleteById(Count::class, 396);
        $result = $em->deleteById(Count::class, 406, true);
        $em->close();
        $this->outputJson([$result->getResult()]);
    }

    /**
     * EM DeleteIds
     */
    public function actionDeleteIds()
    {
        $em = EntityManager::create();
        //        $result = $em->deleteByIds(Count::class, [409, 410]);
        $result = $em->deleteByIds(Count::class, [411, 412], true);
        $em->close();
        $this->outputJson([$result->getResult()]);
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
     * EM find
     */
    public function actionFind()
    {
        $user = new User();
        $user->setSex(1);
        $em = EntityManager::create();
        $query = $em->find($user);
        //        $result = $query->getResult();
        //        $result = $query->getResult(User::class);
        //        $result = $query->getDefer()->getResult();
        $result = $query->getDefer()->getResult(User::class);
        $sql = $query->getSql();
        $em->close();

        $this->outputJson([$result, $sql]);
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
     * EM find id
     */
    public function actionFindId()
    {
        $em = EntityManager::create();
        $query = $em->findById(User::class, 396);
        //        $result = $query->getResult();
        //        $result = $query->getResult(User::class);
        $result = $query->getDefer()->getResult();
        $sql = $query->getSql();
        $em->close();

        $this->outputJson([$result, $sql]);
    }

    /**
     * Ar IDS查找
     */
    public function actionArFindIds()
    {
        $query = User::findByIds([416, 417]);

        $sql = $query->getSql();

        //        $defer = $query->getDefer();
        //        $result = $defer->getResult(User::class);

        $result = $query->getResult();

        $this->outputJson([$result, $sql]);
    }

    /**
     * EM find ids
     */
    public function actionFindIds()
    {
        $em = EntityManager::create();
        $query = $em->findByIds(User::class, [396, 403]);
        $result = $query->getResult();
        //                $result = $query->getResult(User::class);
        //        $result = $query->getDefer()->getResult(User::class);
        $sql = $query->getSql();
        $em->close();

        $this->outputJson([$result, $sql]);
    }

    /**
     * Ar Query
     */
    public function actionArQuery()
    {
        //        $query = User::query()->select('*')->andWhere('sex', 1)->orderBy('id',QueryBuilder::ORDER_BY_DESC)->limit(3);
        //        $query = User::query()->selects(['id', 'sex' => 'sex2'])->andWhere('sex', 1)->orderBy('id',QueryBuilder::ORDER_BY_DESC)->limit(3);
        $query = User::query()->selects(['id', 'sex' => 'sex2'])->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 429)
            ->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2);
        //        $result = $query->getResult();
        $defer = $query->getDefer();
        $result = $defer->getResult();
        $this->outputJson([$result, $query->getSql()]);
    }

    /**
     * EM 事务测试
     */
    public function actionTs()
    {
        $user = new User();
        $user->setName("stelin");
        $user->setSex(1);
        $user->setDesc("this my desc");
        $user->setAge(mt_rand(1, 100));

        $count = new Count();
        $count->setFans(mt_rand(1, 1000));
        $count->setFollows(mt_rand(1, 1000));

        $em = EntityManager::create();
        $em->beginTransaction();
        $uid = $em->save($user);
        $count->setUid($uid);

        $result = $em->save($count);
        if ($result === false) {
            $em->rollback();
        } else {
            $em->commit();
        }
        $em->close();

        $this->outputJson([$uid, $result]);
    }

    public function actionQuery()
    {
        $em = EntityManager::create();
        $query = $em->createQuery();
        $query->select("*")->from(User::class, 'u')->leftJoin(Count::class, ['u.id=c.uid'], 'c')->whereIn('u.id', [419, 420, 421])
            ->orderBy('u.id', QueryBuilder::ORDER_BY_DESC)->limit(2);
        //        $result = $query->getResult();
        $result = $query->getDefer()->getResult();
        $sql = $query->getSql();
        $em->close();

        $this->outputJson([$result, $sql]);
    }

    /**
     * 并发执行两个语句
     */
    public function actionArCon()
    {
        $query1 = User::query()->selects(['id', 'sex' => 'sex2'])->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 419)
            ->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->getDefer();

        $query2 = User::query()->select("*")->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 420)
            ->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->getDefer();

        $result1 = $query1->getResult();
        $result2 = $query2->getResult();
        $this->outputJson([$result1, $result2]);
    }


    public function actionSql()
    {
        $params = [
            ['uid', 433],
            ['uid2', 434],
            ['uid3', 431, Types::INT],
        ];
        $em = EntityManager::create();
//        $querySql = "SELECT * FROM user AS u LEFT JOIN count AS c ON u.id=c.uid WHERE u.id IN (:uid, :uid1, :uid3) ORDER BY u.id DESC LIMIT 2";
//        $query = $em->createQuery($querySql);
//                        $query->setParameter('uid', 433);
//                        $query->setParameter('uid2', 434);
//                        $query->setParameter('uid3', 431);
//        $query->setParameters($params);

                $querySql = "SELECT * FROM user AS u LEFT JOIN count AS c ON u.id=c.uid WHERE u.id IN (?, ?, ?) ORDER BY u.id DESC LIMIT 2";
                $query = $em->createQuery($querySql);
                $query->setParameter(1, 433);
                $query->setParameter(2, 434);
                $query->setParameter(3, 431);

        $result = $query->getResult();
        $sql = $query->getSql();
        $em->close();

        $this->outputJson([$result, $sql]);
    }
}
<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Controllers;

use App\Models\Entity\Count;
use App\Models\Entity\User;
use Swoft\Db\Pool;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Db\EntityManager;
use Swoft\Db\QueryBuilder;
use Swoft\Db\Types;

/**
 * @Controller()
 */
class OrmController
{
    public function arSave()
    {
        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));
        $deferUser = $user->save();

        $count = new Count();
        $count->setUid(999);
        $count->setFans(mt_rand(1, 1000));
        $count->setFollows(mt_rand(1, 1000));
        $deferCount = $count->save();

        $userResult  = $deferUser->getResult();
        $countResult = $deferCount->getResult();

        $user = new User();
        $user->setName('stelin2');
        $user->setSex(1);
        $user->setDesc('this my desc2');
        $user->setAge(mt_rand(1, 100));
        $directUser = $user->save()->getResult();

        $count = new Count();
        $count->setUid($directUser);
        $count->setFans(mt_rand(1, 1000));
        $count->setFollows(mt_rand(1, 1000));
        $directCount = $count->save()->getResult();

        return [$userResult, $countResult, $directUser, $directCount];
    }

    public function test(){
        $sql = 'select * from user';
        $em = EntityManager::create();
        $result = $em->createQuery($sql)->execute()->getResult();
//        $em->close();

        return [$result];
    }

    public function notGetResult(){

        $user2 = User::findById(4212)->getResult();
        $user = User::findById(4212);
        return [$user2];
    }

    /**
     * @return array
     */
    public function notCommitAndClose()
    {
        $user2 = User::findById(4212)->getResult();

        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $em = EntityManager::create();
        $em->beginTransaction();
        $result = $em->save($user)->getResult();

        return [$user2, $result];
    }

    /**
     * @return array
     */
    public function notCommitAndCloseAndGetResult()
    {
        $user2 = User::findById(4212)->getResult();

        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $em = EntityManager::create();
        $em->beginTransaction();
        $result = $em->save($user);

        return [$user2, $result];
    }

    /**
     * @return array
     */
    public function notCommit()
    {
        $user2 = User::findById(4212)->getResult();

        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $em = EntityManager::create();
        $em->beginTransaction();
        $result = $em->save($user)->getResult();
        $em->close();

        return [$user2, $result];
    }

    /**
     * @return array
     */
    public function notCloseButCommit()
    {
        $user2 = User::findById(4212)->getResult();

        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $em = EntityManager::create();
        $em->beginTransaction();
        $result = $em->save($user)->getResult();
        $em->commit();

        return [$user2, $result];
    }

    /**
     * @return array
     */
    public function closeAndNotGetResult()
    {
        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $em = EntityManager::create();
        $user2 = User::findById(4212)->getResult();
        $em->beginTransaction();
        $result = $em->save($user)->getResult();
        $result = $em->save($user);
        $em->commit();
        $em->close();

        return [$user2, $result];
    }

    /**
     * EM查找
     */
    public function save()
    {
        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $em = EntityManager::create();
        //        $result = $em->save($user);
        $result = $em->save($user)->getResult();
        $em->close();

        return [$result];
    }

    public function forceMater()
    {
        $user = User::findById(4212, 'default.master')->getResult();
        return [$user];
    }

    /**
     * 实体内容删除
     */
    public function arDelete()
    {
        $user = new User();
        //        $user->setId(286);
        $user->setAge(126);

        //        $result = $user->delete();
        $defer = $user->delete();

        return $defer->getResult();
    }

    /**
     * Em 删除
     */
    public function delete()
    {
        $user = new User();
        $user->setId(418);

        $em = EntityManager::create();
        //        $result = $em->delete($user);
        $result = $em->delete($user);
        $em->close();

        return [$result->getResult()];
    }

    /**
     * EM deleteId
     */
    public function deleteId()
    {
        $em = EntityManager::create();
        //        $result = $em->deleteById(Count::class, 396);
        $result = $em->deleteById(Count::class, 406);
        $em->close();
        return [$result->getResult()];
    }

    /**
     * EM DeleteIds
     */
    public function deleteIds()
    {
        $em = EntityManager::create();
        //        $result = $em->deleteByIds(Count::class, [409, 410]);
        $result = $em->deleteByIds(Count::class, [411, 412]);
        $em->close();
        return [$result->getResult()];
    }

    /**
     * 删除ID测试
     */
    public function arDeleteId()
    {
        //        $result = User::deleteById(284);
        $result = User::deleteById(287);

        return $result->getResult();
    }

    /**
     * 删除IDs测试
     */
    public function arDeleteIds()
    {
        //        $result = User::deleteByIds([291, 292]);
        $result = User::deleteByIds([288, 289]);

        return $result->getResult();
    }

    /**
     * 更新操作
     */
    public function arUpdate()
    {
        $query = User::findById(285);

        /* @var User $user */
        $user = $query->getResult(User::class);
        $user->setName('upateNameUser2');
        $user->setSex(0);

        $result = $user->update();
        //        $result = $user->update(true);
        //        $result = $result->getResult();

        return [$result->getResult()];
    }

    /**
     * 实体查找
     */
    public function arFind()
    {
        $user = new User();
        $user->setSex(1);
        $user->setAge(93);
        $query = $user->find();

        $result = $query->getResult(User::class);
        return [$result];
    }

    /**
     * EM find
     */
    public function find()
    {
        $user = new User();
        $user->setSex(1);
        $em = EntityManager::create();
        $query = $em->find($user);
        //        $result = $query->getResult();
        //        $result = $query->getResult(User::class);
        //        $result = $query->getDefer()->getResult();
        $result = $query->getResult(User::class);
        $em->close();

        return [$result];
    }

    /**
     * Ar ID查找
     */
    public function arFindId()
    {
        $result = User::findById(4212)->getResult();

        $query = User::findById(4212);

        /* @var User $user */
        $user = $query->getResult(User::class);
        return [$result, $user->getName()];
    }

    /**
     * EM find id
     */
    public function findId()
    {
        $em = EntityManager::create();
        $query = $em->findById(User::class, 396);
        //        $result = $query->getResult();
        //        $result = $query->getResult(User::class);
        $result = $query->getResult();
        $em->close();

        return [$result];
    }

    /**
     * Ar IDS查找
     */
    public function arFindIds()
    {
        $query = User::findByIds([416, 417]);

        //        $defer = $query->getDefer();
        //        $result = $defer->getResult(User::class);

        $result = $query->getResult();

        return [$result];
    }

    /**
     * EM find ids
     */
    public function findIds()
    {
        $em = EntityManager::create();
        $query = $em->findByIds(User::class, [396, 403]);
        $result = $query->getResult();
        //                $result = $query->getResult(User::class);
        //        $result = $query->getDefer()->getResult(User::class);
        $em->close();

        return [$result];
    }

    /**
     * Ar Query
     */
    public function arQuery()
    {
        //        $query = User::query()->select('*')->andWhere('sex', 1)->orderBy('id',QueryBuilder::ORDER_BY_DESC)->limit(3);
        //        $query = User::query()->selects(['id', 'sex' => 'sex2'])->andWhere('sex', 1)->orderBy('id',QueryBuilder::ORDER_BY_DESC)->limit(3);
        $query = User::query()->selects(['id', 'sex' => 'sex2'])->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 429)
            ->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->execute();
        //        $result = $query->getResult();
        $result = $query->getResult();
        return [$result];
    }

    /**
     * EM 事务测试
     */
    public function ts()
    {
        $user = new User();
        $user->setName('stelin');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $user2 = new User();
        $user2->setName('stelin');
        $user2->setSex(1);
        $user2->setDesc('this my desc');
        $user2->setAge(mt_rand(1, 100));

        $count = new Count();
        $count->setFans(mt_rand(1, 1000));
        $count->setFollows(mt_rand(1, 1000));

        $em = EntityManager::create();
        $re = $user2->save()->getResult();
        $em->beginTransaction();

        $uid = $em->save($user)->getResult();
        $count->setUid($uid);

        $result = $em->save($count)->getResult();

        $result2 = $user2->save()->getResult();
        $em->rollback();
//        $em->commit();
        $em->close();

        return [$uid, $result];
    }

    public function query()
    {
        $em = EntityManager::create();
        $query = $em->createQuery();
        $result = $query->select('*')->from(User::class, 'u')->leftJoin(Count::class, ['u.id=c.uid'], 'c')->whereIn('u.id', [419, 420, 421])
                        ->orderBy('u.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->execute();
        //        $result = $query->getResult();
        $result = $result->getResult();
        $em->close();

        return [$result];
    }

    /**
     * 并发执行两个语句
     */
    public function arCon()
    {
        $query1 = User::query()->selects(['id', 'sex' => 'sex2'])->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 419)
            ->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->execute();

        $query2 = User::query()->select('*')->leftJoin(Count::class, 'count.uid=user.id')->andWhere('id', 420)
                      ->orderBy('user.id', QueryBuilder::ORDER_BY_DESC)->limit(2)->execute();

        $result1 = $query1->getResult();
        $result2 = $query2->getResult();
        return [$result1, $result2];
    }

    public function sql()
    {
        $ids = [4212, 4213];
        $poolId = Pool::MASTER;

        $em = EntityManager::create($poolId);
        $result = $em->createQuery('select * from user where id in(?, ?) and name = ? order by id desc limit 2')
            ->setParameter(0, $ids[0])
            ->setParameter(1, $ids[1])
            ->setParameter(2, 'stelin')
            ->execute()->getResult();
        $em->close();

        $em = EntityManager::create($poolId);
        $result2 = $em->createQuery('select * from user where id in(?, ?) and name = ? order by id desc limit 2')
            ->setParameter(0, $ids[0])
            ->setParameter(1, $ids[1])
            ->setParameter(2, 'stelin', Types::STRING)
            ->execute()->getResult();
        $em->close();

        $em = EntityManager::create($poolId);
        $result3 = $em->createQuery('select * from user where id in(?, ?) and name = ? order by id desc limit 2')
            ->setParameters([$ids[0], $ids[1], 'stelin'])
            ->execute()->getResult();
        $em->close();

        $em = EntityManager::create($poolId);
        $result4 = $em->createQuery('select * from user where id in(:id1, :id2) and name = :name order by id desc limit 2')
            ->setParameter(':id1', $ids[0])
            ->setParameter('id2', $ids[1])
            ->setParameter('name', 'stelin')
            ->execute()->getResult();
        $em->close();

        $em = EntityManager::create($poolId);
        $result5 = $em->createQuery('select * from user where id in(:id1, :id2) and name = :name order by id desc limit 2')
            ->setParameters([
                'id1' => $ids[0],
                ':id2' => $ids[1],
                'name' => 'stelin'
            ])
            ->execute()->getResult();
        $em->close();


        $em = EntityManager::create($poolId);
        $result6 = $em->createQuery('select * from user where id in(:id1, :id2) and name = :name order by id desc limit 2')
            ->setParameters([
                ['id1', $ids[0]],
                [':id2', $ids[1], Types::INT],
                ['name', 'stelin', Types::STRING],
            ])
            ->execute()->getResult();
        $em->close();

        return [\count($result)];
    }
}
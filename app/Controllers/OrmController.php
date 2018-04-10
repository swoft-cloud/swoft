<?php
/**
 * This file is part of Swoft.
 *
 * @link    https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Controllers;

use App\Models\Entity\Count;
use App\Models\Entity\User;
use Swoft\Db\Db;
use Swoft\Db\Query;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * @Controller()
 */
class OrmController
{
    public function save()
    {
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save()->getResult();

        return [$userId];
    }

    public function findById()
    {
        $result = User::findById(41710)->getResult();
        $query  = User::findById(41710);

        /* @var User $user */
        $user = $query->getResult(User::class);

        return [$result, $user->getName()];
    }

    public function selectDb(){
        $data = [
            'name' => 'name',
            'sex'  => 1,
            'description' => 'this my desc',
            'age'  => mt_rand(1, 100),
        ];
        $result = Query::table(User::class)->selectDb('test2')->insert($data)->getResult();
        return $result;
    }

    public function selectTable(){
        $data = [
            'name' => 'name',
            'sex'  => 1,
            'description' => 'this my desc',
            'age'  => mt_rand(1, 100),
        ];
        $result = Query::table('user2')->insert($data)->getResult();
        return $result;
    }

    public function transactionCommit()
    {
        Db::beginTransaction();
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save()->getResult();
        Db::commit();


        return $userId;
    }

    public function transactionRollback()
    {
        Db::beginTransaction();

        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save()->getResult();

        $count = new Count();
        $count->setUid($userId);
        $count->setFollows(mt_rand(1, 100));
        $count->setFans(mt_rand(1, 100));

        $countId = $count->save()->getResult();

        Db::rollback();


        return [$userId, $countId];
    }

    /**
     * This is a wrong operation, only used to test
     *
     * @return mixed
     */
    public function transactionNotCommitOrRollback()
    {
        Db::beginTransaction();
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save()->getResult();

        // This is a wrong operation, You must to commit or rollback
        // ...

        return $userId;
    }

    /**
     * This is a wrong operation, only used to test
     *
     * @RequestMapping("tsnonr")
     * @return mixed
     */
    public function transactionNotCommitOrRollbackAndNotGetResult()
    {
        Db::beginTransaction();
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save();

        // This is a wrong operation, You must to commit or rollback
        // ...

        return ['11'];
    }

    /**
     * This is a wrong operation, only used to test
     *
     * @RequestMapping("tsng")
     * @return mixed
     */
    public function transactionNotGetResult()
    {
        Db::beginTransaction();
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save();
        Db::commit();

        return [333];
    }

    /**
     * This is a wrong operation, only used to test
     *
     * @RequestMapping("tsng2")
     * @return mixed
     */
    public function transactionNotGetResult2()
    {
        Db::beginTransaction();
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save();
        Db::rollback();

        return [33];
    }

    /**
     * This is a wrong operation, only used to test
     *
     * @return mixed
     */
    public function notGetResult()
    {
        $result = User::findById(19362);
        $query  = User::findById(19362);

        /* @var User $user */
        $user = $query->getResult(User::class);

        return [33];
    }

    /**
     * This is a wrong operation, only used to test
     *
     * @return mixed
     */
    public function notGetResult2()
    {
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save()->getResult();

        $result = User::findById(19362);
        $query  = User::findById(19362);

        /* @var User $user */
        $user = $query->getResult(User::class);

        return [222];
    }

    /**
     * This is a wrong operation, only used to test
     *
     * @return mixed
     */
    public function notGetResult3()
    {
        $user = new User();
        $user->setName('name');
        $user->setSex(1);
        $user->setDesc('this my desc');
        $user->setAge(mt_rand(1, 100));

        $userId = $user->save();

        $result = User::findById(19362);
        $query  = User::findById(19362);

        /* @var User $user */
        $user = $query->getResult(User::class);

        return [33];
    }
}
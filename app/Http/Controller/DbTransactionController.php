<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\User;
use mysql_xdevapi\Exception;
use Swoft\Db\DB;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class DbTransactionController
 *
 * @since 2.0
 *
 * @Controller("dbTransaction")
 */
class DbTransactionController
{
    /**
     * @RequestMapping(route="ts")
     *
     * @return false|string
     */
    public function ts()
    {
        DB::beginTransaction();
        $user = User::find(1);

        \sgo(function () {
            DB::beginTransaction();
            User::find(1);
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="cm")
     *
     * @return false|string
     */
    public function cm()
    {
        DB::beginTransaction();
        $user = User::find(1);
        DB::commit();

        \sgo(function () {
            DB::beginTransaction();
            User::find(1);
            DB::commit();
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="rl")
     *
     * @return false|string
     */
    public function rl()
    {
        DB::beginTransaction();
        $user = User::find(1);
        DB::rollBack();

        \sgo(function () {
            DB::beginTransaction();
            User::find(1);
            DB::rollBack();
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="ts2")
     *
     * @return false|string
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function ts2()
    {
        DB::connection()->beginTransaction();
        $user = User::find(1);

        \sgo(function () {
            DB::connection()->beginTransaction();
            User::find(1);
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="cm2")
     *
     * @return false|string
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function cm2()
    {
        DB::connection()->beginTransaction();
        $user = User::find(1);
        DB::connection()->commit();

        \sgo(function () {
            DB::connection()->beginTransaction();
            User::find(1);
            DB::connection()->commit();
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="rl2")
     *
     * @return false|string
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function rl2()
    {
        DB::connection()->beginTransaction();
        $user = User::find(1);
        DB::connection()->rollBack();

        \sgo(function () {
            DB::connection()->beginTransaction();
            User::find(1);
            DB::connection()->rollBack();
        });

        return json_encode($user->toArray());
    }
}
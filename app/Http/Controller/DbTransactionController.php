<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\User;
use Swoft\Db\DB;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Throwable;
use function sgo;

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
     * @throws Throwable
     */
    public function ts()
    {
        $id = $this->getId();

        DB::beginTransaction();
        $user = User::find($id);

        sgo(function () use ($id) {
            DB::beginTransaction();
            User::find($id);
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="cm")
     *
     * @return false|string
     * @throws Throwable
     */
    public function cm()
    {
        $id = $this->getId();

        DB::beginTransaction();
        $user = User::find($id);
        DB::commit();

        sgo(function () use ($id) {
            DB::beginTransaction();
            User::find($id);
            DB::commit();
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="rl")
     *
     * @return false|string
     * @throws Throwable
     */
    public function rl()
    {
        $id = $this->getId();

        DB::beginTransaction();
        $user = User::find($id);
        DB::rollBack();

        sgo(function () use ($id) {
            DB::beginTransaction();
            User::find($id);
            DB::rollBack();
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="ts2")
     *
     * @return false|string
     * @throws Throwable
     */
    public function ts2()
    {
        $id = $this->getId();

        DB::connection()->beginTransaction();
        $user = User::find($id);

        sgo(function () use ($id) {
            DB::connection()->beginTransaction();
            User::find($id);
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="cm2")
     *
     * @return false|string
     * @throws Throwable
     */
    public function cm2()
    {
        $id = $this->getId();

        DB::connection()->beginTransaction();
        $user = User::find($id);
        DB::connection()->commit();

        sgo(function () use ($id) {
            DB::connection()->beginTransaction();
            User::find($id);
            DB::connection()->commit();
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="rl2")
     *
     * @return false|string
     * @throws Throwable
     */
    public function rl2()
    {
        $id = $this->getId();

        DB::connection()->beginTransaction();
        $user = User::find($id);
        DB::connection()->rollBack();

        sgo(function () use ($id) {
            DB::connection()->beginTransaction();
            User::find($id);
            DB::connection()->rollBack();
        });

        return json_encode($user->toArray());
    }

    /**
     * @return int
     * @throws Throwable
     */
    public function getId(): int
    {
        $user = new User();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');

        $user->save();

        return $user->getId();
    }
}
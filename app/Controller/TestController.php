<?php

namespace App\Controller;

use App\Model\Entity\User;
use function foo\func;
use Swoft\Db\DB;
use Swoft\Db\DbEvent;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoole\Coroutine;

/**
 * Class TestController
 *
 * @Controller("/test")
 * @since 2.0
 */
class TestController
{
    /**
     * Test
     *
     * @RequestMapping(route="index")
     *
     * @return string
     */
    public function test(): string
    {
        $user = User::find(22);
        sgo(function () {
//            $user = User::find(22);
            User::where('id', '=', 22);
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="ts")
     *
     * @return false|string
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function ts()
    {
        DB::pool()->beginTransaction();
        $user = User::find(22);

        \sgo(function (){
            DB::pool()->beginTransaction();
            $user = User::find(22);
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="cm")
     *
     * @return false|string
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function cm()
    {
        DB::pool()->beginTransaction();
        $user = User::find(22);
        DB::pool()->commit();

        \sgo(function (){
            DB::pool()->beginTransaction();
            $user = User::find(22);
            DB::pool()->commit();
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="rl")
     *
     * @return false|string
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\PoolException
     */
    public function rl()
    {
        DB::pool()->beginTransaction();
        $user = User::find(22);
        DB::pool()->rollBack();

        \sgo(function (){
            DB::pool()->beginTransaction();
            $user = User::find(22);
            DB::pool()->rollBack();
        });

        return json_encode($user->toArray());
    }


    /**
     * @param Response $response
     * @param Request  $request
     *
     * @RequestMapping(route="p")
     *
     * @return string
     */
    public function params(Response $response, Request $request): string
    {
        return get_class($response) . '-' . get_class($request);
    }

    /**
     * @param int      $uid
     * @param Response $response
     * @param string   $name
     * @param int      $age
     * @param int      $count
     *
     * @RequestMapping(route="u/{uid}")
     *
     * @return string
     */
    public function user(int $uid, Response $response, string $name, int $age, int $count = 1): string
    {
        return 'uid=' . $uid . ' name=' . $name . ' age=' . $age . ' count=' . $count . ' response=' . get_class($response);
    }
}
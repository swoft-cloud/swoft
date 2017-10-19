<?php

namespace App\Tasks;

use App\Models\Entity\Count;
use App\Models\Entity\User;
use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Bean\Annotation\Scheduled;
use Swoft\Bean\Annotation\Task;
use Swoft\Db\EntityManager;
use Swoft\Http\HttpClient;
use Swoft\Redis\Cache\RedisClient;
use Swoft\Service\Service;

/**
 * 测试task
 *
 * @uses      TestTask
 * @Task("test")
 * @version   2017年09月24日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class TestTask
{
    /**
     * 协程task
     *
     * @param mixed $p1
     * @param mixed $p2
     *
     * @return string
     */
    public function corTask($p1, $p2)
    {
        static $status = 1;
        $status++;
        echo "this cor task \n";
        App::trace("this is task log");
        //        RedisClient::set('name', 'stelin boy');
        $name = RedisClient::get('name');
        return 'cor' . " $p1" . " $p2 " . $status . " " . $name;
    }

    public function testMysql()
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
        $count->setUid(intval($uid));

        $result = $em->save($count);
        if ($result === false) {
            $em->rollback();
        } else {
            $em->commit();
        }
        $em->close();
        return $result;
    }

    public function testHttp()
    {
        $requestData = [
            'name' => 'boy',
            'desc' => 'php'
        ];

        $result = HttpClient::call("http://127.0.0.1/index/post?a=b", HttpClient::GET, $requestData);
        $result2 = HttpClient::call("http://www.baidu.com/", HttpClient::GET, []);
        $data['result'] = $result;
        $data['result2'] = $result2;
        return $data;
    }

    public function testRpc()
    {
        var_dump('^^^^^^^^^^^', ApplicationContext::getContext());
        App::trace("this rpc task worker");
        $result = Service::call("user", 'User::getUserInfo', [2,6,8]);
        return $result;
    }


    /**
     * 异步task
     *
     * @return string
     */
    public function asyncTask()
    {
        static $status = 1;
        $status++;
        echo "this async task \n";
        $name = RedisClient::get('name');
        App::trace("this is task log");
        return 'async-' . $status . '-' . $name;
    }

    /**
     * @Scheduled(cron="0 0/1 8-20 * * ?")
     */
    public function cronTask()
    {
        echo "this cron task  \n";
        return 'cron';
    }
}
<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Tasks;

use App\Lib\DemoInterface;
use App\Models\Entity\User;
use Swoft\App;
use Swoft\Bean\Annotation\Inject;
use Swoft\HttpClient\Client;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use Swoft\Task\Bean\Annotation\Scheduled;
use Swoft\Task\Bean\Annotation\Task;

/**
 * Sync task
 *
 * @Task("sync")
 */
class SyncTask
{
    /**
     * @Reference("user")
     *
     * @var DemoInterface
     */
    private $demoService;

    /**
     * @Inject()
     * @var \App\Models\Logic\UserLogic
     */
    private $logic;

    /**
     * Deliver co task
     *
     * @param string $p1
     * @param string $p2
     *
     * @return string
     */
    public function deliverCo(string $p1, string $p2)
    {
        App::profileStart('co');
        App::trace('trace');
        App::info('info');
        App::pushlog('key', 'stelin');
        App::profileEnd('co');

        return sprintf('deliverCo-%s-%s', $p1, $p2);
    }

    /**
     * Deliver async task
     *
     * @param string $p1
     * @param string $p2
     *
     * @return string
     */
    public function deliverAsync(string $p1, string $p2)
    {
        App::profileStart('co');
        App::trace('trace');
        App::info('info');
        App::pushlog('key', 'stelin');
        App::profileEnd('co');

        return sprintf('deliverCo-%s-%s', $p1, $p2);
    }

    /**
     * Cache task
     *
     * @return string
     */
    public function cache()
    {
        cache()->set('cacheKey', 'cache');

        return cache('cacheKey');
    }

    /**
     * Mysql task
     *
     * @return array
     */
    public function mysql(){
        $result = User::findById(720)->getResult();

        $query = User::findById(720);

        /* @var User $user */
        $user = $query->getResult(User::class);
        return [$result, $user->getName()];
    }

    /**
     * Http task
     *
     * @return mixed
     */
    public function http()
    {
        $client = new Client();
        $response = $client->get('http://www.swoft.org')->getResponse()->getBody()->getContents();
        $response2 = $client->get('http://127.0.0.1/redis/testCache')->getResponse()->getBody()->getContents();

        $data['result1'] = $response;
        $data['result2'] = $response2;
        return $data;
    }

    /**
     * Rpc task
     *
     * @return mixed
     */
    public function rpc()
    {
        return $this->demoService->getUser('6666');
    }

    /**
     * Rpc task
     *
     * @return mixed
     */
    public function rpc2()
    {
        return $this->logic->rpcCall();
    }

    /**
     * crontab定时任务
     * 每一秒执行一次
     *
     * @Scheduled(cron="* * * * * *")
     */
    public function cronTask()
    {
        echo time() . "每一秒执行一次  \n";
        return 'cron';
    }

    /**
     * 每分钟第3-5秒执行
     *
     * @Scheduled(cron="3-5 * * * * *")
     */
    public function cronooTask()
    {
        echo time() . "第3-5秒执行\n";
        return 'cron';
    }
}

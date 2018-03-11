<?php

namespace App\Controllers;


use App\Models\Entity\User;
use Swoft\Bean\Annotation\Inject;
use Swoft\Cache\Cache;
use Swoft\Http\Server\Bean\Annotation\Controller;


/**
 * @Controller(prefix="/redis")
 */
class RedisController
{
    /**
     * @Inject("cache")
     * @var Cache
     */
    private $cache;

    /**
     * @Inject()
     * @var \Swoft\Redis\Redis
     */
    private $redis;

    public function testCache()
    {
        $result = $this->cache->set('name', 'swoft');
        $name   = $this->cache->get('name');

        $this->redis->incr("count");

        $this->redis->incrBy("count2", 2);

        return [$result, $name, $this->redis->get('count'), $this->redis->get('count2'), '3'];
    }

    public function testRedis()
    {
        $result = $this->redis->set('nameRedis', 'swoft2');
        $name   = $this->redis->get('nameRedis');

        return [$result, $name];
    }

    public function ab()
    {
        $result1 = User::query()->select('*')->where('id', '720')->limit(1)->execute()->getResult();
        $result2 = $this->redis->set('test1', 1);

        return [$result1, $result2];
    }

    public function testFunc()
    {
        $result = cache()->set('nameFunc', 'swoft3');
        $name   = cache()->get('nameFunc');

        return [$result, $name];
    }

    public function testFunc2()
    {
        $result = cache()->set('nameFunc2', 'swoft3');
        $name   = cache('nameFunc2');
        $name2   = cache('nameFunc3', 'value3');

        return [$result, $name, $name2];
    }

    public function testDelete()
    {
        $result = $this->cache->set('name', 'swoft');
        $del    = $this->cache->delete('name');

        return [$result, $del];
    }

    public function clear()
    {
        $result = $this->cache->clear();

        return [$result];
    }

    public function setMultiple()
    {
        $result = $this->cache->setMultiple(['name6' => 'swoft6', 'name8' => 'swoft8']);
        $ary    = $this->cache->getMultiple(['name6', 'name8']);

        return [$result, $ary];
    }

    public function deleteMultiple()
    {
        $result = $this->cache->setMultiple(['name6' => 'swoft6', 'name8' => 'swoft8']);
        $ary    = $this->cache->deleteMultiple(['name6', 'name8']);

        return [$result, $ary];
    }

    public function has()
    {
        $result = $this->cache->set("name666", 'swoft666');
        $ret    = $this->cache->has('name666');

        return [$result, $ret];
    }

    public function testDefer()
    {
        $ret1 = $this->redis->deferCall('set', ['name1', 'swoft1']);
        $ret2 = $this->redis->deferCall('set', ['name2', 'swoft2']);

        $r1 = $ret1->getResult();
        $r2 = 1;
        $r2 = $ret2->getResult();

        $ary = 1;
        // $ary = $this->redis->getMultiple(['name1', 'name2']);

        return [$r1, $r2, $ary];
    }
}
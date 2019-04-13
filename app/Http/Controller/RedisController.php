<?php declare(strict_types=1);


namespace App\Http\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Redis\Redis;

/**
 * Class RedisController
 *
 * @since 2.0
 * @Controller("redis")
 */
class RedisController
{
    /**
     * @RequestMapping("str")
     */
    public function str(): array
    {
        $key    = 'key';
        $result = Redis::set($key, 'key');

        $keyVal = Redis::get($key);

        $data = [
            $result,
            $keyVal
        ];

        return $data;
    }

    /**
     * Auto release connection
     *
     * @RequestMapping("release")
     *
     * @return array
     * @throws \Swoft\Redis\Exception\RedisException
     */
    public function release(): array
    {
        \sgo(function () {
            Redis::connection();
        });

        Redis::connection();

        return ['release'];
    }
}
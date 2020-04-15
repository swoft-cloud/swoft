<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use InvalidArgumentException;
use Swoft\Cache\Cache;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class CacheController
 *
 * @since 2.0.8
 *
 * @Controller()
 */
class CacheController
{
    /**
     * @RequestMapping()
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function set(): array
    {
        $ok  = Cache::set('ckey', 'cache value');
        $ok1 = Cache::set('ckey1', 'cache value2', 5);

        return ['ckey' => $ok, 'ckey1' => $ok1];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function get(): array
    {
        $val = Cache::get('ckey');

        return [
            'ckey'  => $val,
            'ckey1' => Cache::get('ckey1')
        ];
    }

    /**
     * @RequestMapping("del")
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function del(): array
    {
        /** @var Response $resp */
        // $resp = context()->getResponse();

        return ['del' => Cache::delete('ckey')];
    }
}

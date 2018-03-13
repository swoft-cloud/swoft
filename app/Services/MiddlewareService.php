<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Services;

use App\Lib\MdDemoInterface;
use App\Middlewares\ServiceMiddleware;
use App\Middlewares\ServiceSubMiddleware;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * the middleware of service
 *
 * @Service()
 * @Middlewares({
 *     @Middleware(ServiceSubMiddleware::class)
 * })
 */
class MiddlewareService implements MdDemoInterface
{
    /**
     * @return array
     */
    public function parentMiddleware()
    {
        return ['pm'];
    }

    /**
     * @Middleware(class=ServiceMiddleware::class)
     * @return array
     */
    public function funcMiddleware()
    {
        return ['fm'];
    }
}
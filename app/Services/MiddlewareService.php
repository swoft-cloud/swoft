<?php

namespace App\Services;

use Swoft\Bean\Annotation\Middleware;
use Swoft\Bean\Annotation\Middlewares;
use App\Middlewares\ServiceMiddleware;
use App\Middlewares\ServiceSubMiddleware;
use Swoft\Rpc\Server\Bean\Annotation\Service;
use Swoft\Rpc\Server\Bean\Annotation\Mapping;

/**
 * the middleware of service
 *
 * @Service("Md")
 * @Middlewares({
 *     @Middleware(ServiceSubMiddleware::class)
 * })
 * @uses      MiddlewareService
 * @version   2017年12月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MiddlewareService
{
    /**
     * @Mapping("pm")
     *
     * @return array
     */
    public function parentMiddleware()
    {
        return ['pm'];
    }

    /**
     * @Mapping("fm")
     *
     * @Middleware(class=ServiceMiddleware::class)
     * @return array
     */
    public function funcMiddleware()
    {
        return ['fm'];
    }
}
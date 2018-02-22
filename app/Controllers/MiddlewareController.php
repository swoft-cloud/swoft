<?php

namespace App\Controllers;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use App\Middlewares\GroupTestMiddleware;
use App\Middlewares\ActionTestMiddleware;
use App\Middlewares\SubMiddleware;
use App\Middlewares\ControlerSubMiddleware;
use App\Middlewares\ControlerTestMiddleware;


/**
 * @Controller("md")
 *
 * @Middleware(class=ControlerTestMiddleware::class)
 * @Middlewares({
 *     @Middleware(ControlerSubMiddleware::class)
 * })
 */
class MiddlewareController
{
    /**
     * @RequestMapping(route="caa")
     *
     * @Middlewares({
     *     @Middleware(GroupTestMiddleware::class),
     *     @Middleware(ActionTestMiddleware::class)
     * })
     * @Middleware(SubMiddleware::class)
     */
    public function controllerAndAction()
    {
        return ['middleware'];
    }

    /**
     * @RequestMapping(route="caa2")
     *
     * @Middleware(SubMiddleware::class)
     * @Middlewares({
     *     @Middleware(GroupTestMiddleware::class),
     *     @Middleware(ActionTestMiddleware::class)
     * })
     */
    public function controllerAndAction2()
    {
        return ['middleware2'];
    }

    /**
     * @RequestMapping("cm")
     */
    public function controlerMiddleware()
    {
        return ['middleware3'];
    }


}
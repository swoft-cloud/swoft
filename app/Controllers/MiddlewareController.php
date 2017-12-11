<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\Controller;
use Swoft\Bean\Annotation\Middleware;
use Swoft\Bean\Annotation\Middlewares;
use Swoft\Bean\Annotation\RequestMapping;
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
 *
 * @uses      MiddlewareController
 * @version   2017年11月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
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
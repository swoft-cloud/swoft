<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\Controller;
use Swoft\Bean\Annotation\Middleware;
use Swoft\Bean\Annotation\Middlewares;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Web\Request;
use Swoft\Web\Response;
use App\Middlewares\GroupTestMiddleware;
use App\Middlewares\ActionTestMiddleware;
use App\Middlewares\SubMiddleware;

/**
 * action demo
 *
 * @Controller(prefix="/route")
 *
 * @Middleware(SubMiddleware::class)
 *
 * @uses      TestController
 * @version   2017年11月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RouteController
{
    /**
     * @RequestMapping()
     */
    public function index()
    {
        return 'index';
    }

    /**
     * @RequestMapping(route="user/{uid}/book/{bid}/{bool}/{name}")
     *
     * @param bool                $bool
     * @param \Swoft\Web\Request  $request
     * @param int                 $bid
     * @param string              $name
     * @param int                 $uid
     * @param \Swoft\Web\Response $response
     *
     * @return array
     */
    public function actionFuncArgs(bool $bool, Request $request, int $bid, string $name, int $uid, Response $response)
    {
        return [$bid, $uid, $bool, $name, get_class($request), get_class($response)];
    }

    /**
     * @RequestMapping(route="hasNotArg")
     *
     * @return string
     */
    public function actionHasNotArgs()
    {
        return 'hasNotArg';
    }

    /**
     * @RequestMapping(route="hasAnyArgs/{bid}")
     * @param \Swoft\Web\Request $request
     * @param int                $bid
     *
     * @return string
     */
    public function actionHasAnyArgs(Request $request, int $bid)
    {
        return [get_class($request), $bid];
    }

    /**
     * @RequestMapping(route="hasMoreArgs")
     *
     * @param \Swoft\Web\Request $request
     * @param int                $bid
     *
     * @return array
     */
    public function actionHasMoreArgs(Request $request, int $bid)
    {
        return [get_class($request), $bid];
    }

    /**
     * optional parameter
     *
     * @RequestMapping(route="opntion[/{name}]")
     *
     * @param string $name
     * @return array
     */
    public function actionOptionalParameter(string $name)
    {
        return[$name];
    }

    /**
     * optional parameter
     *
     * @RequestMapping(route="anyName/{name}")
     *
     * @param string $name
     * @return array
     */
    public function funcAnyName(string $name)
    {
        return [$name];
    }

    /**
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function actionNotAnnotation(Request $request)
    {
        return [get_class($request)];
    }

    /**
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function onlyFunc(Request $request)
    {
        return [get_class($request)];
    }

    /**
     * @param \Swoft\Web\Request $request
     *
     * @return array
     */
    public function BehindAction(Request $request)
    {
        return [get_class($request)];
    }

    /**
     * @RequestMapping(route="middlewares")
     *
     * @Middlewares({
     *     @Middleware(GroupTestMiddleware::class),
     *     @Middleware(ActionTestMiddleware::class)
     * })
     * @Middleware(SubMiddleware::class)
     */
    public function actionMiddleware()
    {
        return ['middleware'];
    }

}
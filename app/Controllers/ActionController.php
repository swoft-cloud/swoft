<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Web\Request;
use Swoft\Web\Response;

/**
 * action demo
 *
 * @AutoController(prefix="/action")
 * @uses      TestController
 * @version   2017年11月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ActionController
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
     * @param \Swoft\Web\Request  $request
     * @param \Swoft\Web\Response $response
     * @param int                 $bid
     * @param int                 $uid
     *
     * @return array
     */
    public function actionTest(bool $bool, Request $request,  int $bid, string $name, int $uid, Response $response)
    {
        return ['test', $bid, $uid, $bool, $name, Request::class, Response::class];
    }
}
<?php

namespace Swoft\Test\Web;

/**
 * route phpunit
 *
 * @uses      RouteTest
 * @version   2017年11月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RouteTest extends AbstractTestCase
{
    /**
     * @covers \App\Controllers\RouteController@actionFuncArgs
     */
    public function testFuncArgs()
    {
        $data     = [
            456,
            123,
            true,
            "test",
            "Swoft\\Testing\\Web\\Request",
            "Swoft\\Testing\\Web\\Response",
        ];
        $response = $this->request('GET', '/route/user/123/book/456/1/test', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers closure route
     */
    public function testClosureFuncArgs()
    {
        $data     = [
            'clouse',
            456,
            123,
            true,
            "test",
            "Swoft\\Testing\\Web\\Request",
            "Swoft\\Testing\\Web\\Response",
        ];
        $response = $this->request('GET', '/user/123/book/456/1/test', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RouteController@actionHasNotArgs
     */
    public function testHasNotArg()
    {
        $response = $this->request('GET', '/route/hasNotArg', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['data' => 'hasNotArg']);
    }

    /**
     * @covers \App\Controllers\RouteController@actionHasAnyArgs
     */
    public function testHasAnyArgs()
    {
        $response = $this->request('GET', '/route/hasAnyArgs/123', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["Swoft\\Testing\\Web\\Request", 123]);
    }
}
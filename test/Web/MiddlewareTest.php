<?php

namespace Swoft\Test\Web;

/**
 * middleware teste
 *
 * @uses      MiddlewareTest
 * @version   2017年11月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MiddlewareTest extends AbstractTestCase
{
    /**
     * @covers \App\Controllers\MiddlewareController@controllerAndAction
     */
    public function testControllerAndAction()
    {
        $response = $this->request('GET', '/md/caa', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["middleware"]);
        $response->assertHeader('Middleware-Group-Test', 'success');
        $response->assertHeader('Sub-Middleware-Test', 'Success');
        $response->assertHeader('Middleware-Action-Test', 'success');
    }

    /**
     * @covers \App\Controllers\MiddlewareController@controllerAndAction2
     */
    public function testControllerAndAction2()
    {
        $response = $this->request('GET', '/md/caa2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["middleware2"]);
        $response->assertHeader('Middleware-Group-Test', 'success');
        $response->assertHeader('Sub-Middleware-Test', 'Success');
        $response->assertHeader('Middleware-Action-Test', 'success');
    }

    /**
     * @covers \App\Controllers\MiddlewareController@controlerMiddleware
     */
    public function testControlerMiddleware()
    {
        $response = $this->request('GET', '/md/cm', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["middleware3"]);
        $response->assertHeader('ControlerTestMiddleware', 'success');
        $response->assertHeader('ControlerSubMiddleware', 'success');
    }
}
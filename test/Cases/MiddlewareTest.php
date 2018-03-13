<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Test\Cases;

/**
 * Middleware test case
 */
class MiddlewareTest extends AbstractTestCase
{
    /**
     * @covers \App\Controllers\MiddlewareController::action1
     * @test
     */
    public function action1()
    {
        $response = $this->request('GET', '/middleware/action1', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['middleware']);
        $response->assertHeader('Middleware-Group-Test', 'success');
        $response->assertHeader('Sub-Middleware-Test', 'success');
        $response->assertHeader('Middleware-Action-Test', 'success');
    }

    /**
     * @covers \App\Controllers\MiddlewareController::action2
     * @test
     */
    public function action2()
    {
        $response = $this->request('GET', '/middleware/action2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['middleware2']);
        $response->assertHeader('Middleware-Group-Test', 'success');
        $response->assertHeader('Sub-Middleware-Test', 'success');
        $response->assertHeader('Middleware-Action-Test', 'success');
    }

    /**
     * @covers \App\Controllers\MiddlewareController::action3
     * @test
     */
    public function action3()
    {
        $response = $this->request('GET', '/middleware/action3', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['middleware3']);
        $response->assertHeader('Controller-Test-Middleware', 'success');
        $response->assertHeader('Controller-Sub-Middleware', 'success');
    }
}
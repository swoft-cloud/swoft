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
 * route phpunit
 *
 * @uses      RouteTest
 * @version   2017年11月27日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
use Swoft\Http\Message\Testing\Web\Request;
use Swoft\Http\Message\Testing\Web\Response;

class RouteTest extends AbstractTestCase
{
    /**
     * @covers \App\Controllers\RouteController@funcArgs
     */
    public function testFuncArgs()
    {
        $data     = [
            456,
            123,
            true,
            'test',
            Request::class,
            Response::class,
        ];
        $response = $this->request('GET', '/route/user/123/book/456/1/test', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RouteController::hasNotArgs
     */
    public function testHasNotArg()
    {
        $response = $this->request('GET', '/route/hasNotArg', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['data' => 'hasNotArg']);
    }

    /**
     * @covers \App\Controllers\RouteController@hasAnyArgs
     */
    public function testHasAnyArgs()
    {
        $response = $this->request('GET', '/route/hasAnyArgs/123', [], parent::ACCEPT_JSON);
        $response->assertExactJson([Request::class, 123]);
    }

    /**
     * @covers \App\Controllers\RouteController@optionalParameter
     */
    public function testOptionnalParameter()
    {
        $response = $this->request('GET', '/route/opntion/arg1', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['arg1']);

        $response = $this->request('GET', '/route/opntion', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['']);
    }

    /**
     * @covers \App\Controllers\RouteController@hasMoreArgs
     */
    public function testHasMoreArgs()
    {
        $response = $this->request('GET', '/route/hasMoreArgs', [], parent::ACCEPT_JSON);
        $response->assertExactJson([Request::class, 0]);
    }

    /**
     * @covers \App\Controllers\RouteController@notAnnotation
     */
    public function testNotAnnotation()
    {
        $response = $this->request('GET', '/route/notAnnotation', [], parent::ACCEPT_JSON);
        $response->assertExactJson([Request::class]);
    }

    /**
     * @covers \App\Controllers\RouteController@onlyFunc
     */
    public function testOnlyFunc()
    {
        $response = $this->request('GET', '/route/onlyFunc', [], parent::ACCEPT_JSON);
        $response->assertExactJson([Request::class]);
    }

    /**
     * @covers \App\Controllers\RouteController@behind
     */
    public function testBehindAction()
    {
        $response = $this->request('GET', '/route/behind', [], parent::ACCEPT_JSON);
        $response->assertExactJson([Request::class]);
    }

    /**
     * @covers \App\Controllers\RouteController@funcAnyName
     */
    public function testFuncAnyName()
    {
        $response = $this->request('GET', '/route/anyName/stelin', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['stelin']);
    }
}
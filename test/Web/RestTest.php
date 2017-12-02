<?php

namespace Swoft\Test\Web;

/**
 * the test of restful
 *
 * @uses      RestTest
 * @version   2017年11月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RestTest extends AbstractTestCase
{
    /**
     * @covers \App\Controllers\RestController@actionList
     */
    public function testList()
    {
        $data     = ["list"];
        $response = $this->request('GET', '/users', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@actionCreate
     */
    public function testCreate()
    {
        $data     = ["create","stelin"];
        $response = $this->request('POST', '/users', ['name' => 'stelin'], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@actionGetUser
     */
    public function testGetUser()
    {
        $data     = ["getUser",123];
        $response = $this->request('GET', '/users/123', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@actionGetBookFromUser
     */
    public function testGetBookFromUser()
    {
        $data     = ["bookFromUser",123,"456"];
        $response = $this->request('GET', '/users/123/book/456', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

//    /**
//     * @covers \App\Controllers\RestController@actionDeleteUser
//     */
//    public function testDeleteUser()
//    {
//        $data     = ["bookFromUser",123,"456"];
//        $response = $this->request('DELETE', '/users/uid', [], parent::ACCEPT_JSON);
//        $response->assertExactJson($data);
//    }
}
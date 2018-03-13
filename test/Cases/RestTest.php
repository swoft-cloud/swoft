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
     * @covers \App\Controllers\RestController@list
     */
    public function testList()
    {
        $data     = ['list'];
        $response = $this->request('GET', '/user', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@create
     */
    public function testCreate()
    {
        $data     = ['create', 'stelin'];
        $response = $this->request('POST', '/user', ['name' => 'stelin'], parent::ACCEPT_JSON);
        $response->assertExactJson($data);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $content = '{"name":"stelin","age":18,"desc":"swoft framework"}';
        $data     = [
            'name' => 'stelin',
            'age' => 18,
            'desc' => 'swoft framework',
        ];
        $response = $this->request('PUT', '/user', [], parent::ACCEPT_JSON, $headers, $content);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@getUser
     */
    public function testGetUser()
    {
        $data     = ['getUser',123];
        $response = $this->request('GET', '/user/123', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@getBookFromUser
     */
    public function testGetBookFromUser()
    {
        $data     = ['bookFromUser',123, '456'];
        $response = $this->request('GET', '/user/123/book/456', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@deleteUser
     */
    public function testDeleteUser()
    {
        $data     = ['delete',123];
        $response = $this->request('DELETE', '/user/123', [], parent::ACCEPT_JSON);
        $response->assertExactJson($data);
    }

    /**
     * @covers \App\Controllers\RestController@updateUser
     */
    public function testUpdateUser()
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $content = '{"name":"stelin","age":18,"desc":"swoft framework"}';
        $data     = [
            'name' => 'stelin',
            'age' => 18,
            'desc' => 'swoft framework',
            'update' => 'update',
            'uid' => 123
        ];

        $response = $this->request('PUT', '/user/123', [], parent::ACCEPT_JSON, $headers, $content);
        $response->assertExactJson($data);

        $response = $this->request('PATCH', '/user/123', [], parent::ACCEPT_JSON, $headers, $content);
        $response->assertExactJson($data);
    }
}
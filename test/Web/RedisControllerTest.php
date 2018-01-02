<?php

namespace Web;

use Swoft\Test\Web\AbstractTestCase;


/**
 * @uses      RedisControllerTest
 * @version   2017年11月30日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisControllerTest extends AbstractTestCase
{

    public function testCache()
    {
        $expected = [
            true,
            'stelin',
        ];
        $response = $this->request('GET', '/redis/testCache', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    public function testRedis()
    {
        $expected = [
            true,
            'stelin2',
        ];
        $response = $this->request('GET', '/redis/testRedis', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    public function testFunc()
    {
        $expected = [
            true,
            'stelin3',
        ];
        $response = $this->request('GET', '/redis/testFunc', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    public function testDelete()
    {
        $expected = [
            true,
            1,
        ];
        $response = $this->request('GET', '/redis/testDelete', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    public function testClear()
    {
        $expected = [
            true,
        ];
        $response = $this->request('GET', '/redis/clear', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    public function testMultiple()
    {
        $expected = [
            true,
            [
                'stelin6',
                'stelin8',
            ],
        ];
        $response = $this->request('GET', '/redis/setMultiple', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    public function testDeleteMultiple()
    {
        $expected = [
            true,
            2,
        ];
        $response = $this->request('GET', '/redis/deleteMultiple', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    public function testHas()
    {
        $expected = [
            true,
            true,
        ];
        $response = $this->request('GET', '/redis/has', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }
}
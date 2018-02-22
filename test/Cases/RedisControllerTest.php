<?php

namespace Swoft\Test\Cases;

/**
 * @uses      RedisControllerTest
 * @version   2017年11月30日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisControllerTest extends AbstractTestCase
{

    /**
     * @test
     * @requires extension redis
     */
    public function cache()
    {
        $expected = [
            true,
            'stelin',
        ];
        $response = $this->request('GET', '/redis/testCache', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    /**
     * @test
     * @requires extension redis
     */
    public function redis()
    {
        $expected = [
            true,
            'stelin2',
        ];
        $response = $this->request('GET', '/redis/testRedis', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    /**
     * @test
     * @requires extension redis
     */
    public function func()
    {
        $expected = [
            true,
            'stelin3',
        ];
        $response = $this->request('GET', '/redis/testFunc', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    /**
     * @test
     * @requires extension redis
     */
    public function func2()
    {
        $expected = [
            true,
            'stelin3',
            'value3',
        ];
        $response = $this->request('GET', '/redis/testFunc2', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    /**
     * @test
     * @requires extension redis
     */
    public function delete()
    {
        $expected = [
            true,
            1,
        ];
        $response = $this->request('GET', '/redis/testDelete', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    /**
     * @test
     * @requires extension redis
     */
    public function clear()
    {
        $expected = [
            true,
        ];
        $response = $this->request('GET', '/redis/clear', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    /**
     * @test
     * @requires extension redis
     */
    public function multiple()
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

    /**
     * @test
     * @requires extension redis
     */
    public function deleteMultiple()
    {
        $expected = [
            true,
            2,
        ];
        $response = $this->request('GET', '/redis/deleteMultiple', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

    /**
     * @test
     * @requires extension redis
     */
    public function has()
    {
        $expected = [
            true,
            true,
        ];
        $response = $this->request('GET', '/redis/has', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }
}
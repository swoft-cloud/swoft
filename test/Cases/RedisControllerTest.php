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

use Swoft\Redis\Redis;

/**
 * Class RedisControllerTest
 *
 * @package Swoft\Test\Cases
 */
class RedisControllerTest extends AbstractTestCase
{

    protected $isRedisConnected = false;

    protected function setUp()
    {
        parent::setUp();
        $redis = bean(Redis::class);
        try {
            $redis->has('test');
            $this->isRedisConnected = true;
        } catch (\Exception $e) {
            // No connection or else error
        }
    }

    /**
     * @param \Closure $closure
     */
    protected function runRedisTest(\Closure $closure)
    {
        if ($this->isRedisConnected) {
            $closure();
        } else {
            $this->markTestSkipped('No redis connection');
        }
    }

    /**
     * @test
     * @requires extension redis
     */
    public function cache()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                'swoft',
            ];
            $response = $this->request('GET', '/redis/testCache', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function redis()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                'swoft2',
            ];
            $response = $this->request('GET', '/redis/testRedis', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function func()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                'swoft3',
            ];
            $response = $this->request('GET', '/redis/testFunc', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function func2()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                'swoft3',
                'value3',
            ];
            $response = $this->request('GET', '/redis/testFunc2', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function delete()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                1,
            ];
            $response = $this->request('GET', '/redis/testDelete', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function clear()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
            ];
            $response = $this->request('GET', '/redis/clear', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function multiple()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                [
                    'name6' => 'swoft6',
                    'name8' => 'swoft8',
                ],
            ];
            $response = $this->request('GET', '/redis/setMultiple', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function deleteMultiple()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                2,
            ];
            $response = $this->request('GET', '/redis/deleteMultiple', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function has()
    {
        $this->runRedisTest(function () {
            $expected = [
                true,
                true,
            ];
            $response = $this->request('GET', '/redis/has', [], parent::ACCEPT_JSON);
            $response->assertSuccessful()->assertJson($expected);
        });
    }
}
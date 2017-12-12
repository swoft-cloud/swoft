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

    /**
     * @test
     * @requires extension redis
     * @covers \App\Controllers\RedisController
     */
    public function testTest()
    {
        $expected = [
            'setResult' => true,
            'getResult' => 123321
        ];
        $response = $this->request('GET', '/redis/test', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson($expected);
    }

}
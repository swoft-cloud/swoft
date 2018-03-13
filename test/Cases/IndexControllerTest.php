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

use Swoft\Http\Message\Testing\Web\Response;

/**
 * @uses      IndexControllerTest
 * @version   2017-11-12
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class IndexControllerTest extends AbstractTestCase
{

    /**
     * @test
     * @covers \App\Controllers\IndexController
     */
    public function testIndex()
    {
        $expectedResult = [
            'name' => 'Swoft',
            'notes' => [
                'New Generation of PHP Framework',
                'Hign Performance, Coroutine and Full Stack'
            ],
            'links' => [
                [
                    'name' => 'Home',
                    'link' => 'http://www.swoft.org',
                ],
                [
                    'name' => 'Documentation',
                    'link' => 'http://doc.swoft.org',
                ],
                [
                    'name' => 'Case',
                    'link' => 'http://swoft.org/case',
                ],
                [
                    'name' => 'Issue',
                    'link' => 'https://github.com/swoft-cloud/swoft/issues',
                ],
                [
                    'name' => 'GitHub',
                    'link' => 'https://github.com/swoft-cloud/swoft',
                ],
            ]
        ];

        $jsonAssert = function ($response) use ($expectedResult) {
            $this->assertInstanceOf(Response::class, $response);
            /** @var Response $response */
            $response->assertSuccessful()
                ->assertHeaderContain('Content-Type', 'application/json')
                ->assertSee('Swoft')
                ->assertSeeText('New Generation of PHP Framework')
                ->assertDontSee('Swoole')
                ->assertDontSeeText('Swoole')
                ->assertJson(['name' => 'Swoft'])
                ->assertExactJson($expectedResult)
                ->assertJsonFragment(['name' => 'Home'])
                ->assertJsonMissing(['name' => 'Swoole'])
                ->assertJsonStructure(['name', 'notes']);
        };
        // Json model
        $response = $this->request('GET', '/', [], parent::ACCEPT_JSON);
        $response->assertHeaderContain('Content-Type', parent::ACCEPT_JSON);
        $jsonAssert($response);

        // Raw model
        $response = $this->request('GET', '/', [], parent::ACCEPT_RAW);
        $response->assertHeaderContain('Content-Type', parent::ACCEPT_JSON);
        $jsonAssert($response);

        // View model
        $response = $this->request('GET', '/', [], parent::ACCEPT_VIEW);
        $response->assertSuccessful()
            ->assertSee($expectedResult['name'])
            ->assertSee($expectedResult['notes'][0])
            ->assertSee($expectedResult['notes'][1])
            ->assertHeaderContain('Content-Type', 'text/html');

        // absolutePath
        $response = $this->request('GET', '/index/absolutePath', [], parent::ACCEPT_VIEW);
        $response->assertSuccessful()
            ->assertSee($expectedResult['name'])
            ->assertSee($expectedResult['notes'][0])
            ->assertSee($expectedResult['notes'][1])
            ->assertHeader('Content-Type', 'text/html');
    }

    /**
     * @test
     * @covers \App\Controllers\IndexController
     */
    public function testException()
    {
        $data = [
            'message' => 'bad request exception'
        ];

        $response = $this->request('GET', '/index/exception', [], parent::ACCEPT_JSON);
        $response->assertJson($data);
    }

    /**
     * @test
     * @covers \App\Controllers\IndexController
     */
    public function testRaw()
    {
        $expected = 'Swoft';
        $response = $this->request('GET', '/index/raw', [], parent::ACCEPT_RAW);
        $response->assertSuccessful()->assertSee($expected);

        $response = $this->request('GET', '/index/raw', [], parent::ACCEPT_JSON);
        $response->assertSuccessful()->assertJson(['data' => $expected]);
    }

}

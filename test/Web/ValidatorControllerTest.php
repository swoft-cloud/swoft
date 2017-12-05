<?php

namespace Swoft\Test\Web;

/**
 * validator test
 *
 * @uses      ValidatorControllerTest
 * @version   2017年12月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ValidatorControllerTest extends AbstractTestCase
{
    /**
     * @covers \App\Controllers\ValidatorController@string
     */
    public function testIndex()
    {
        $response = $this->request('GET', '/validator/string/stelin', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["boy","girl","stelin"]);

        $response = $this->request('POST', '/validator/string/c', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["message" => "c is too small (minimum is 3)"]);

        $response = $this->request('POST', '/validator/string/stelin', ['name' => 'a'], parent::ACCEPT_JSON);
        $response->assertExactJson(["message" => "a is too small (minimum is 3)"]);

        $response = $this->request('POST', '/validator/string/stelin?name=b', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["message" => "b is too small (minimum is 3)"]);

        $response = $this->request('POST', '/validator/string/stelin66666666', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["message" => "stelin66666666 is too big (maximum is 10)"]);

        $response = $this->request('POST', '/validator/string/stelin', ['name' => 'stelin66666666'], parent::ACCEPT_JSON);
        $response->assertExactJson(["message" => "stelin66666666 is too big (maximum is 10)"]);

        $response = $this->request('POST', '/validator/string/stelin?name=stelin66666666', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["message" => "stelin66666666 is too big (maximum is 10)"]);

        $response = $this->request('POST', '/validator/string/stelinPath?name=stelinGet', ['name' => 'stelinPost'], parent::ACCEPT_JSON);
        $response->assertExactJson(["stelinGet","stelinPost","stelinPath"]);
    }
}
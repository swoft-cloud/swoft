<?php

namespace Swoft\Test\Cases;

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
    public function testString()
    {
        $response = $this->request('GET', '/validator/string/stelin', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['boy', 'girl', 'stelin']);

        $response = $this->request('POST', '/validator/string/c', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'c is too small (minimum is 3)']);

        $response = $this->request('POST', '/validator/string/stelin', ['name' => 'a'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'a is too small (minimum is 3)']);

        $response = $this->request('POST', '/validator/string/stelin?name=b', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'b is too small (minimum is 3)']);

        $response = $this->request('POST', '/validator/string/stelin66666666', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'stelin66666666 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/string/stelin', ['name' => 'stelin66666666'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'stelin66666666 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/string/stelin?name=stelin66666666', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'stelin66666666 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/string/stelinPath?name=stelinGet', ['name' => 'stelinPost'], parent::ACCEPT_JSON);
        $response->assertExactJson(['stelinGet', 'stelinPost', 'stelinPath']);
    }

    /**
     * @covers \App\Controllers\ValidatorController@number
     */
    public function testNumber()
    {
        $response = $this->request('GET', '/validator/number/10', [], parent::ACCEPT_JSON);
        $response->assertExactJson([7, 8, 10]);

        $response = $this->request('POST', '/validator/number/3', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '3 is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/number/6', ['id' => '-2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '-2 is not number']);

        $response = $this->request('POST', '/validator/number/6', ['id' => '2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '2 is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/number/6?id=-2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '-2 is not number']);

        $response = $this->request('POST', '/validator/number/6?id=2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '2 is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/number/12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '12 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/number/9', ['id' => '12'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '12 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/number/9?id=12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '12 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/number/9?id=9', ['id' => '9'], parent::ACCEPT_JSON);
        $response->assertExactJson(['9', '9', 9]);
    }

    /**
     * @covers \App\Controllers\ValidatorController@float
     */
    public function testFloat()
    {
        $response = $this->request('GET', '/validator/float/a', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'a is not float']);

        $response = $this->request('GET', '/validator/float/5', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '5 is not float']);

        $response = $this->request('POST', '/validator/float/5.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '5 is too small (minimum is 5.1)']);

        $response = $this->request('POST', '/validator/float/6.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '6 is too big (maximum is 5.9)']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => 5], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '5 is not float']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => '5.0'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '5 is too small (minimum is 5.1)']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => '6.0'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '6 is too big (maximum is 5.9)']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => '5.2'], parent::ACCEPT_JSON);
        $response->assertExactJson([5.6, '5.2', 5.2]);

        $response = $this->request('POST', '/validator/float/5.2?id=5', [5.2], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '5 is not float']);

        $response = $this->request('POST', '/validator/float/5.2?id=5.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '5 is too small (minimum is 5.1)']);

        $response = $this->request('POST', '/validator/float/5.2?id=6.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '6 is too big (maximum is 5.9)']);


        $response = $this->request('POST', '/validator/float/5.2?id=5.2', ['id' => '5.2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['5.2', '5.2', 5.2]);
    }

    /**
     * @covers \App\Controllers\ValidatorController@integer
     */
    public function testInteger()
    {
        $response = $this->request('GET', '/validator/integer/10', [], parent::ACCEPT_JSON);
        $response->assertExactJson([7, 8, 10]);

        $response = $this->request('POST', '/validator/integer/3', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '3 is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/integer/6', ['id' => 'a'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'a is not integer']);

        $response = $this->request('POST', '/validator/integer/6', ['id' => '2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '2 is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/integer/6?id=a', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'a is not integer']);

        $response = $this->request('POST', '/validator/integer/6?id=2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '2 is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/integer/12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '12 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/integer/9', ['id' => '12'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '12 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/integer/9?id=12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '12 is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/integer/9?id=9', ['id' => '9'], parent::ACCEPT_JSON);
        $response->assertExactJson(['9', '9', 9]);
    }

    /**
     * @covers \App\Controllers\ValidatorController@enum
     */
    public function testEnum()
    {
        $response = $this->request('POST', '/validator/enum/4', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '4 is not valid enum!']);

        $response = $this->request('POST', '/validator/enum/1?name=4', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '4 is not valid enum!']);

        $response = $this->request('POST', '/validator/enum/1', ['name' => '4'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => '4 is not valid enum!']);

        $response = $this->request('POST', '/validator/enum/1?name=a', ['name' => '3'], parent::ACCEPT_JSON);
        $response->assertExactJson(['a', '3', '1']);
    }
}
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
 * validator test
 *
 * @uses      ValidatorControllerTest
 * @version   2017年12月03日
 * @author    swoft <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ValidatorControllerTest extends AbstractTestCase
{
    /**
     * @covers \App\Controllers\ValidatorController::string
     */
    public function testString()
    {
        $response = $this->request('GET', '/validator/string/swoft', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['boy', 'girl', 'swoft']);

        $response = $this->request('POST', '/validator/string/c', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name length is too short (minimum is 3)']);

        $response = $this->request('POST', '/validator/string/swoft', ['name' => 'a'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name length is too short (minimum is 3)']);

        $response = $this->request('POST', '/validator/string/swoft?name=b', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name length is too short (minimum is 3)']);

        $response = $this->request('POST', '/validator/string/swoft66666666', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name length is too long (maximum is 10)']);

        $response = $this->request('POST', '/validator/string/swoft', ['name' => 'swoft66666666'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name length is too long (maximum is 10)']);

        $response = $this->request('POST', '/validator/string/swoft?name=swoft66666666', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name length is too long (maximum is 10)']);

        $response = $this->request('POST', '/validator/string/swoftPath?name=swoftGet', ['name' => 'swoftPost'], parent::ACCEPT_JSON);
        $response->assertExactJson(['swoftGet', 'swoftPost', 'swoftPath']);
    }

    /**
     * @covers \App\Controllers\ValidatorController::number
     */
    public function testNumber()
    {
        $response = $this->request('GET', '/validator/number/10', [], parent::ACCEPT_JSON);
        $response->assertExactJson([7, 8, 10]);

        $response = $this->request('POST', '/validator/number/3', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/number/6', ['id' => '-2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not a number']);

        $response = $this->request('POST', '/validator/number/6', ['id' => '2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/number/6?id=-2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not a number']);

        $response = $this->request('POST', '/validator/number/6?id=2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/number/12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/number/9', ['id' => '12'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/number/9?id=12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/number/9?id=9', ['id' => '9'], parent::ACCEPT_JSON);
        $response->assertExactJson(['9', '9', 9]);
    }

    /**
     * @covers \App\Controllers\ValidatorController::float
     */
    public function testFloat()
    {
        $response = $this->request('GET', '/validator/float/a', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not float type']);

        $response = $this->request('GET', '/validator/float/5', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not float type']);

        $response = $this->request('POST', '/validator/float/5.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/float/6.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 5)']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => 5], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not float type']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => '5.0'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => '6.0'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 5)']);

        $response = $this->request('POST', '/validator/float/5.2', ['id' => '5.2'], parent::ACCEPT_JSON);
        $response->assertExactJson([5.6, '5.2', 5.2]);

        $response = $this->request('POST', '/validator/float/5.2?id=5', [5.2], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not float type']);

        $response = $this->request('POST', '/validator/float/5.2?id=5.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/float/5.2?id=6.0', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 5)']);


        $response = $this->request('POST', '/validator/float/5.2?id=5.2', ['id' => '5.2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['5.2', '5.2', 5.2]);
    }

    /**
     * @covers \App\Controllers\ValidatorController::integer
     */
    public function testInteger()
    {
        $response = $this->request('GET', '/validator/integer/10', [], parent::ACCEPT_JSON);
        $response->assertExactJson([7, 8, 10]);

        $response = $this->request('POST', '/validator/integer/3', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/integer/6', ['id' => 'a'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not integer type']);

        $response = $this->request('POST', '/validator/integer/6', ['id' => '2'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/integer/6?id=a', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is not integer type']);

        $response = $this->request('POST', '/validator/integer/6?id=2', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too small (minimum is 5)']);

        $response = $this->request('POST', '/validator/integer/12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/integer/9', ['id' => '12'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/integer/9?id=12', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter id is too big (maximum is 10)']);

        $response = $this->request('POST', '/validator/integer/9?id=9', ['id' => '9'], parent::ACCEPT_JSON);
        $response->assertExactJson(['9', '9', 9]);
    }

    /**
     * @covers \App\Controllers\ValidatorController::enum
     */
    public function testEnum()
    {
        $response = $this->request('POST', '/validator/enum/4', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name is an invalid enum value']);

        $response = $this->request('POST', '/validator/enum/1?name=4', [], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name is an invalid enum value']);

        $response = $this->request('POST', '/validator/enum/1', ['name' => '4'], parent::ACCEPT_JSON);
        $response->assertExactJson(['message' => 'Parameter name is an invalid enum value']);

        $response = $this->request('POST', '/validator/enum/1?name=a', ['name' => '3'], parent::ACCEPT_JSON);
        $response->assertExactJson(['a', '3', '1']);
    }
}
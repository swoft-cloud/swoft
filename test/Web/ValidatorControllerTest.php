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
    public function testIndex()
    {
        $response = $this->request('GET', '/validator/index/123', [], parent::ACCEPT_JSON);
        $response->assertExactJson(["Swoft\\Testing\\Web\\Request"]);
    }
}
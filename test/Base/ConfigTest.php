<?php

namespace Swoft\Test\Base;


use Swoft\App;
use Swoft\Base\Config;
use Swoft\Test\AbstractTestCase;

/**
 * @uses      ConfigTest
 * @version   2017年11月03日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ConfigTest extends AbstractTestCase
{

    public function testConfig()
    {
        /** @var \Swoft\Base\Config $config */
        $config = App::getBean('config');
        $this->assertInstanceOf(Config::class, $config);
        $value = 1;
        $offset = 'test';
        $this->assertFalse($config->offsetExists($offset));
        $config->offsetSet($offset, $value);
        $this->assertEquals($value, $config->current());
        $this->assertEquals($value, $config->offsetGet($offset));
        $this->assertTrue($config->offsetExists($offset));
    }
}
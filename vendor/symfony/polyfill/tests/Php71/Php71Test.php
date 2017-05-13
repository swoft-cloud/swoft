<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Polyfill\Tests\Php71;

class Php71Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideIsIterable
     */
    public function testIsIterable($expected, $var)
    {
        $this->assertSame($expected, is_iterable($var));
    }

    public function provideIsIterable()
    {
        return array(
            array(true, array(1, 2, 3)),
            array(true, new \ArrayIterator(array(1, 2, 3))),
            array(false, 1),
            array(false, 3.14),
            array(false, new \stdClass()),
        );
    }
}

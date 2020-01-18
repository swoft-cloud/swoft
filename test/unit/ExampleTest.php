<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace AppTest\Unit;

use PHPUnit\Framework\TestCase;
use function bean;

class ExampleTest extends TestCase
{
    public function testDemo(): void
    {
        $this->assertNotEmpty(bean('cliApp'));
    }
}

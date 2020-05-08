<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace AppTest\Unit\Common;

use App\Common\MyBean;
use PHPUnit\Framework\TestCase;
use function bean;

/**
 * Class MyBeanTest
 *
 * @package AppTest\Unit\Common
 */
class MyBeanTest extends TestCase
{
    public function testMyMethod2(): void
    {
        $bean = bean(MyBean::class);

        $this->assertSame(MyBean::class . '::myMethod2', $bean->myMethod2());
    }
}

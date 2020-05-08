<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace AppTest\Api;

use PHPUnit\Framework\TestCase;
use Swoft\Swlib\HttpClient;

/**
 * Class ExampleApiTest
 *
 * @package AppTest\Api
 */
class ExampleApiTest extends TestCase
{
    /**
     * @var HttpClient
     */
    private $http;

    public function setUp(): void
    {
        $this->http = new HttpClient();
    }

    public function testHi(): void
    {
        $w = $this->http->get('http://127.0.0.1/hi');

        $this->assertSame('hi', $w->getBody()->getContents());
    }
}

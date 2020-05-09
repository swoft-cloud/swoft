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

use App\Http\Controller\HomeController;
use PHPUnit\Framework\TestCase;
use Swoft\Swlib\HttpClient;

/**
 * Class ExampleApiTest
 *
 * @package AppTest\Api
 */
class ExampleApiTest extends TestCase
{
    public const HOST = 'http://127.0.0.1:18306';

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
        /** @see HomeController::hi() */
        $w = $this->http->get(self::HOST. '/hi');

        $this->assertSame(200, $w->getStatusCode());
        $this->assertSame('hi', $w->getBody()->getContents());
    }
}

<?php declare(strict_types=1);

namespace App\Tcp\Controller;

use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;

/**
 * Class DemoController
 *
 * @TcpController()
 */
class DemoController
{
    /**
     * @TcpMapping("echo")
     */
    public function index(): void
    {

    }

    /**
     * @TcpMapping("echo", root=true)
     */
    public function echo(): void
    {

    }
}

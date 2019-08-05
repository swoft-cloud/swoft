<?php declare(strict_types=1);

namespace App\WebSocket;

use App\WebSocket\Test\TestController;
use Swoft\Http\Message\Request;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\MessageParser\TokenTextParser;

/**
 * Class TestModule
 *
 * @WsModule(
 *     "/test",
 *     defaultCommand="test.index",
 *     messageParser=TokenTextParser::class,
 *     controllers={TestController::class}
 * )
 */
class TestModule
{
    /**
     * @OnOpen()
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        Session::mustGet()->push("Opened, welcome!(FD: $fd)");
    }
}

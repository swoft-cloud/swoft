<?php declare(strict_types=1);

namespace App\WebSocket;

use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoft\WebSocket\Server\MessageParser\JsonParser;
use Swoole\WebSocket\Server;

/**
 * Class AbstractModule
 * @since 2.0
 *
 * @WsModule(name="chat", path="/chat", messageParser=JsonParser::class)
 */
class ChatModule implements WsModuleInterface
{
    public function onError(\Throwable $e, int $fd): void
    {

    }

    public function checkHandshake(\Swoft\Http\Message\Request $request, \Swoft\Http\Message\Response $response): array
    {
        return [];
    }

    public function onOpen(Server $server, \Swoft\Http\Message\Request $request, int $fd): void
    {
    }

    public function onClose(Server $server, int $fd): void
    {
    }
}

<?php declare(strict_types=1);

namespace App\WebSocket;

use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\Contract\WsModuleInterface;
use Swoole\Http\ServerRequest;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Swoft\WebSocket\Server\MessageParser\JsonParser;
use Swoft\WebSocket\Server\Annotation\Mapping\OnClose;
use Swoft\WebSocket\Server\Annotation\Mapping\OnHandShake;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;

/**
 * Class AbstractModule
 * @since 2.0
 *
 * @WsModule(path="/chat", messageParser=JsonParser::class)
 */
class ChatModule implements WsModuleInterface
{
    //
}

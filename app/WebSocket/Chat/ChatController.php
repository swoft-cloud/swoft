<?php declare(strict_types=1);

namespace App\WebSocket\Chat;

use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use App\WebSocket\ChatModule;

/**
 * Class ChatController
 *
 * @WsController("chat", module=ChatModule::class)
 */
class ChatController
{
    /**
     * @MessageMapping()
     */
    public function send(): void
    {

    }

    /**
     * @MessageMapping()
     */
    public function notify(): void
    {

    }
}

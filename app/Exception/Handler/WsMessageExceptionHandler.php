<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Log\Helper\Log;
use Swoft\WebSocket\Server\Exception\Handler\AbstractMessageErrorHandler;
use Swoole\WebSocket\Frame;
use Throwable;
use function server;
use const APP_DEBUG;

/**
 * Class WsMessageExceptionHandler
 *
 * @since 2.0
 *
 * @ExceptionHandler(\Throwable::class)
 */
class WsMessageExceptionHandler extends AbstractMessageErrorHandler
{
    /**
     * @param Throwable $e
     * @param Frame     $frame
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function handle(Throwable $e, Frame $frame): void
    {
        $message = sprintf('%s At %s line %d', $e->getMessage(), $e->getFile(), $e->getLine());

        Log::error('Ws server error(%s)', $message);

        // Debug is false
        if (!APP_DEBUG) {
            server()->push($frame->fd, $e->getMessage());
            return;
        }

        server()->push($frame->fd, $message);
    }
}

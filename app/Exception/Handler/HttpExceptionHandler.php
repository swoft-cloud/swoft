<?php declare(strict_types=1);

namespace App\Exception\Handler;

use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;

/**
 * Class HttpExceptionHandler
 *
 * @ExceptionHandler(Exception::class)
 */
class HttpExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param \Throwable $e
     * @param Response   $response
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(\Throwable $e, Response $response): Response
    {
        if (!\APP_DEBUG) {
            return $response->withStatus(500)->withContent(
                \sprintf(' %s At %s line %d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        $data = [
            'code'  => $e->getCode(),
            'error' => $e->getMessage(),
            'file'  => \sprintf('At %s line %d', $e->getFile(), $e->getLine()),
            'trace' => $e->getTraceAsString(),
        ];

        // Debug is true
        return $response->withData($data);
    }
}

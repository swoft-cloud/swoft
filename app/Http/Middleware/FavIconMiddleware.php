<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use function context;

/**
 * Class FavIconMiddleware
 *
 * @Bean()
 */
class FavIconMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUriPath() === '/favicon.ico') {
            return context()->getResponse()->withStatus(404);
        }

        return $handler->handle($request);
    }
}

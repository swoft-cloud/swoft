<?php declare(strict_types=1);


namespace App\Rpc\Middleware;


use Swoft\Rpc\Server\Contract\MiddlewareInterface;
use Swoft\Rpc\Server\Contract\RequestHandlerInterface;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoft\Rpc\Server\Contract\ResponseInterface;

/**
 * Class ServiceMiddleware
 *
 * @since 2.0
 */
class ServiceMiddleware implements MiddlewareInterface
{
    public function process(RequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        return $requestHandler->handle($request);
    }
}
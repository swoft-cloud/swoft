<?php

namespace App\Middlewares;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Base\RequestHandler;
use Swoft\Bean\Annotation\Bean;
use Swoft\Middleware\MiddlewareInterface;


/**
 * @Bean()
 * @uses      SubMiddlewares
 * @version   2017年11月18日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SubMiddlewares implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Interop\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($handler instanceof RequestHandler) {
            $handler->insertMiddlewares([
                SubMiddleware::class,
            ]);
        }
        return $handler->handle($request);
    }
}
<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use function context;
use function strpos;

/**
 * Class DomainLimitMiddleware
 *
 * @Bean()
 */
class DomainLimitMiddleware implements MiddlewareInterface
{
    private $domain2paths = [
        'user.com' => [
            // match all /user/*
            '/user/',
        ],
        'blog.com' => [
            // match all /blog/*
            '/blog/',
        ]
    ];

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request $request
     * @param RequestHandlerInterface        $handler
     *
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uriPath = $request->getUriPath();
        $domain  = $request->getUri()->getHost();

        if (!isset($this->domain2paths[$domain])) {
            return context()->getResponse()->withStatus(404)->withContent('invalid request domain');
        }

        foreach ($this->domain2paths[$domain] as $prefix) {
            // not match route prefix
            if (strpos($uriPath, $prefix) !== 0) {
                return context()->getResponse()->withStatus(404)->withContent('page not found');
            }
        }

        return $handler->handle($request);
    }
}

<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class CookieController
 *
 * @since 2.0
 *
 * @Controller()
 */
class CookieController
{
    /**
     * @RequestMapping("set")
     *
     * @return Response
     */
    public function set(): Response
    {
        /** @var Response $resp */
        $resp = context()->getResponse();

        return $resp->setCookie('c-name', 'c-value')->withData(['hello']);
    }

    /**
     * @RequestMapping()
     *
     * @param Request $request
     *
     * @return array
     */
    public function get(Request $request): array
    {
        return $request->getCookieParams();
    }

    /**
     * @RequestMapping("del")
     *
     * @return Response
     */
    public function del(): Response
    {
        /** @var Response $resp */
        $resp = context()->getResponse();

        return $resp->delCookie('c-name')->withData(['ok']);
    }
}

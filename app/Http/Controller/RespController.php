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

use Swoft\Exception\SwoftException;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class BeanController
 *
 * @since 2.0
 *
 * @Controller(prefix="resp")
 */
class RespController
{
    /**
     * @RequestMapping()
     *
     * @return Response
     * @throws SwoftException
     */
    public function cookie(): Response
    {
        /** @var Response $resp */
        $resp = context()->getResponse();

        return $resp->setCookie('c-name', 'c-value')->withData(['hello']);
    }
}

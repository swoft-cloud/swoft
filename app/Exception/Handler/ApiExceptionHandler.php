<?php declare(strict_types=1);
/**
 * +----------------------------------------------------------------------
 * | 自定义异常处理器
 * +----------------------------------------------------------------------
 * | Copyright (c) 2019 http://www.sunnyos.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Date：2019-05-22 15:58:50
 * | Author: Sunny (admin@mail.sunnyos.com) QQ：327388905
 * +----------------------------------------------------------------------
 */

namespace App\Exception\Handler;


use App\Exception\ApiException;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Throwable;

/**
 * @ExceptionHandler(ApiException::class)
 */
class ApiExceptionHandler extends AbstractHttpErrorHandler
{

    /**
     * @param Throwable $e
     * @param Response $response
     *
     * @return Response
     */
    public function handle(Throwable $except, Response $response): Response
    {
        $data = [
            'code'  => $except->getCode(),
            'error' => sprintf('(%s) %s', get_class($except), $except->getMessage()),
            'file'  => sprintf('At %s line %d', $except->getFile(), $except->getLine()),
            'trace' => $except->getTraceAsString(),
        ];
       return $response->withData($data);
    }
}

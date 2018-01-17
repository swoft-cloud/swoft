<?php

namespace App\Exception;

use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Exception\RuntimeException;
use Exception;
use Swoft\Http\Message\Server\Response;

/**
 * the handler of global exception
 *
 * @ExceptionHandler()
 * @uses      Handler
 * @version   2018年01月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SwoftExceptionHandler
{
    /**
     * @Handler(Exception::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerException(Response $response, \Throwable $throwable)
    {
        $file = $throwable->getFile();
        $code = $throwable->getCode();
        $exception = $throwable->getMessage();

        return $response->json([$exception, $file, $code]);
    }

    /**
     * @Handler(RuntimeException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerRuntimeException(Response $response, \Throwable $throwable)
    {
        $file = $throwable->getFile();
        $code = $throwable->getCode();
        $exception = $throwable->getMessage();

        return $response->json([$exception, 'runtimeException']);
    }
}
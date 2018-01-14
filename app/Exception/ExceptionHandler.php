<?php

namespace App\Exception;

use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Exception\RuntimeException;
use Exception;

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
     */
    public function handlerException()
    {

    }

    /**
     * @Handler(RuntimeException::class)
     */
    public function handlerRuntimeException()
    {

    }
}
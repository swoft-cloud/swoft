<?php
namespace swoft\exception;

use swoft\base\RequestContext;

/**
 *
 *
 * @uses      ErrorHandler
 * @version   2017年07月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ErrorHandler
{
    public function init(){
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handlerException']);
        set_error_handler([$this, 'handlerError']);
        register_shutdown_function([$this, 'handlerFataError']);
    }

    public function handlerException(\Throwable $e)
    {
        $message = sprintf(
            "Exception: %s\nCalled At %s, Line: %d\nCatch the exception by: %s\nCode Trace:\n%s\n",
            // $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            get_class($e),
            $e->getTraceAsString()
        );
        echo $message;
//        RequestContext::getResponse()->setResponseContent(" ERROR INFO");
//        RequestContext::getResponse()->send();
    }

    public function handlerError($code, $message, $file, $line){
        var_dump($code, $message, $file, $line);
//        RequestContext::getResponse()->setResponseContent(" ERROR INFO");
//        RequestContext::getResponse()->send();
    }

    public function handlerFataError()
    {

        if ($error = error_get_last()) {
            var_dump($error);
        }

//        RequestContext::getResponse()->setResponseContent(" ERROR INFO");
//        RequestContext::getResponse()->send();
    }
}

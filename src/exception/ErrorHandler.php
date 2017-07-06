<?php
namespace swoft\exception;
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
        register_shutdown_function([$this, 'handlerError']);
    }

    public function handlerException($exception)
    {
        var_dump($exception);
    }

    public function handlerError($code, $message, $file, $line){
        var_dump($code, $message);
    }

    public function handlerFataError()
    {
        $error = error_get_last();
        var_dump($error);
    }
}
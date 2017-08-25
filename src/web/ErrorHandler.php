<?php

namespace swoft\web;

use app\controllers\ErrorController;
use swoft\App;

/**
 * 错误处理
 *
 * @uses      ErrorHandler
 * @version   2017年07月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ErrorHandler
{
    /**
     * @var string 统一错误error
     */
//    private $errorAction = "/error/index";
    private $errorAction = "";

    /**
     * 注册错误监听器
     */
    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handlerException']);
        set_error_handler([$this, 'handlerError']);
        register_shutdown_function([$this, 'handlerFataError']);
    }

    /**
     * 处理未捕获异常
     *
     * @param \Throwable $exception
     */
    public function handlerException(\Throwable $exception)
    {
        $this->renderException($exception);
    }

    /**
     * 处理错误
     *
     * @param string $code
     * @param string $message
     * @param string $file
     * @param int    $line
     */
    public function handlerError($code, $message, $file, $line)
    {
        $exception = new \ErrorException($message, $code, $code, $file, $line);
        $this->renderException($exception);
    }

    /**
     * 处理致命错误
     */
    public function handlerFataError()
    {
        $error = error_get_last();
        if (!empty($error)) {
            $exception = new \ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            $this->renderException($exception);
        }
    }

    /**
     * 显示错误
     *
     * @param \Throwable $exception
     *
     * @throws \Throwable
     */
    public function renderException(\Throwable $exception)
    {
        if (!App::isWorkerStatus()) {
            throw $exception;
        }


        $reponse = App::getResponse();
        $reponse->setException($exception);

        App::$app->runController($this->errorAction);
    }
}

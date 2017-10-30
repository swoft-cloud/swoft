<?php

namespace Swoft\Web;

use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Base\Coroutine;
use Swoft\Base\RequestContext;

/**
 * 错误处理
 *
 * @uses      ErrorHandler
 * @version   2017年07月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ErrorHandler
{
    /**
     * 注册错误监听器
     */
    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handlerException']);
        set_error_handler([$this, 'handlerError']);
        register_shutdown_function([$this, 'handlerFatalError']);
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
    public function handlerFatalError()
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
        // 当前命令行
        $context = ApplicationContext::getContext();

        if ($context === ApplicationContext::CONSOLE) {
            throw $exception;
        }

        // 记录错误日志
        App::error($exception);

        // 当前worker进程的顶级协程ID
        $cid = Coroutine::tid();

        if ($response = RequestContext::getResponse($cid)) {
            $response->setException($exception);

            $errorAction = App::$app->getErrorAction();
            App::$app->runController($errorAction);
        }
    }
}

<?php

namespace app\controllers;

use swoft\App;
use swoft\di\annotation\Bean;
use swoft\helpers\ResponseHelper;
use swoft\web\Controller;

/**
 * 错误控制器
 *
 * @Bean()
 * @uses      ErrorController
 * @version   2017年08月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ErrorController extends Controller
{
    /**
     * 错误action
     */
    public function actionIndex()
    {
        $response = App::getResponse();
        $exception = $response->getException();

        $status = $exception->getCode();
        $message  = $exception->getMessage();
        $line = $exception->getLine();
        $file = $exception->getFile();

        $message .= " ".$file." ".$line;
        $this->outputJson("error", $message, $status);
    }
}
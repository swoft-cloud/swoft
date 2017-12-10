<?php

namespace App\Controllers;

use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\View;
use Swoft\Web\Controller;

/**
 * 错误控制器
 * @AutoController()
 *
 * @uses      ErrorController
 * @version   2017年08月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ErrorController extends Controller
{
    /**
     * 错误action
     * @RequestMapping()
     */
    public function actionIndex()
    {
        $response = App::getResponse();
        $exception = $response->getException();

        $status = $exception->getCode();
        $message = $exception->getMessage();
        $line = $exception->getLine();
        $file = $exception->getFile();

        $message .= " " . $file . " " . $line;
        return ['message' => $message];
    }
}

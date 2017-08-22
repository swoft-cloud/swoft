<?php

namespace app\controllers;

use swoft\di\annotation\AutoController;
use swoft\di\annotation\RequestMapping;
use swoft\web\Controller;
use swoft\di\annotation\RequestMethod;

/**
 *
 * @AutoController("/demo2")
 *
 * @uses      DemoController
 * @version   2017年08月22日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DemoController extends Controller
{

    /**
     * @RequestMapping(route="index", method={RequestMethod::GET, RequestMethod::POST})
     */
    public function actionIndex()
    {
        $this->outputJson("suc");
    }

    /**
     * @RequestMapping(route="/index2", method=RequestMethod::GET)
     */
    public function actionIndex2()
    {
        $this->outputJson("suc");
    }
}
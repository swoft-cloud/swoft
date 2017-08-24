<?php

namespace app\controllers;

use app\models\logic\IndexLogic;
use swoft\di\annotation\AutoController;
use swoft\di\annotation\Inject;
use swoft\di\annotation\RequestMapping;
use swoft\web\Controller;
use swoft\di\annotation\RequestMethod;

/**
 * 控制器demo
 *
 * @AutoController(prefix="/demo2")
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
     * 注入逻辑层
     *
     * @Inject()
     * @var IndexLogic
     */
    private $logic;

    /**
     * 定义一个route,支持get和post方式，处理uri=/demo2/index
     *
     * @RequestMapping(route="index", method={RequestMethod::GET, RequestMethod::POST})
     */
    public function actionIndex()
    {
        // 获取所有GET参数
        $get = $this->get();
        // 获取name参数默认值defaultName
        $name = $this->get('name', 'defaultName');
        // 获取所有POST参数
        $post = $this->post();
        // 获取name参数默认值defaultName
        $name = $this->post('name', 'defaultName');
        // 获取所有参，包括GET或POST
        $request = $this->request();
        // 获取name参数默认值defaultName
        $name = $this->request('name', 'defaultName');
        //json方式显示数据


        $this->outputJson("suc");
    }

    /**
     * 定义一个route,支持get,以"/"开头的定义，直接是根路径，处理uri=/index2
     *
     * @RequestMapping(route="/index2", method=RequestMethod::GET)
     */
    public function actionIndex2()
    {
        // 重定向一个URI
//        $this->redirect("/login/index");
        $this->outputJson("suc2");
    }

    /**
     * 没有使用注解，自动解析注入，默认支持get和post
     */
    public function actionIndex3()
    {
        $this->outputJson("suc3");
    }
}
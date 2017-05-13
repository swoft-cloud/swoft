<?php

namespace app\controllers;

use app\models\logic\IndexLogic;
use swoft\Swf;
use swoft\web\Controller;
use swoft\web\Request;

/**
 *
 *
 * @uses      IndexController
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class IndexController extends Controller
{
    /**
     * @Inject
     * @var IndexLogic
     */
    private $logic;

    public function actionIndex(Request $request)
    {
        $data = $this->logic->getUser();
        $data['params'] = Swf::$app->params();
        $this->outputJson($data, 'suc');
    }

    public function actionHtml()
    {
        $data = [
            'name' => 'stelin'
        ];
        $this->render('/main/layout.html', $data);
    }
}
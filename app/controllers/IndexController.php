<?php

namespace app\controllers;

use app\models\logic\IndexLogic;
use DI\Annotation\Inject;
use swoft\base\ApplicationContext;
use swoft\log\FileHandler;
use swoft\log\Logger;
use swoft\rpc\RpcClient;
use swoft\service\Service;
use swoft\App;
use swoft\web\Controller;
use swoft\web\Request;
use swoft\web\Response;

/**
 *
 *
 * @uses      IndexController
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class IndexController extends Controller
{
    /**
     * @Inject()
     * @var IndexLogic
     */
    private $logic;

    public function actionIndex(Request $request, Response $response)
    {
        $data = $this->logic->getUser();
        $data['properties'] = App::$properties['env'];
        $data['count'] = App::$app->count;
        $data['request'] = $request->getRequestUri();

        App::profileStart("logger");
        App::profileStart("logger1");

        App::info("my info log");
        App::info("my2 info log");

        App::error("my error log");
        App::warning("my warning log");

        App::pushlog("pushlogKey", "pushlogVal");
        App::pushlog("pushlogKey2", "pushlogVal2");

        App::profileEnd("logger");
        App::profileEnd("logger1");

        App::counting("redis.get", 1, 10);
        App::counting("redis.get", 1, 10);
        App::counting("redis.set", 1, 10);


        $this->outputJson($data, 'suc');
    }

    public function actionLogin()
    {

        $this->outputJson(array('login suc'), 'suc');
    }

    public function actionHtml()
    {
        $data = [
            'name' => 'stelin'
        ];

        App::profileStart("logger");

        App::info("my info log");
        App::info("my2 info log");

        App::error("my error log");
        App::warning("my warning log");

        App::pushlog("status", 200);

        App::profileEnd("logger");

        App::counting("redis.get", 1, 10);

        $this->render('/main/layout.html', $data);
    }

    public function actionRpc()
    {
        $result = Service::call("user", 'User::getUserInfo', [2,6,8]);

        $res = Service::deferCall("user", 'User::getUserInfo', [3,6,9]);
        $users = $res->getResult();

        $data['count'] = App::$app->count;
        $data['ret'] = $result;
        $data['deferRet'] = $users;
        $this->outputJson($data);
    }
}

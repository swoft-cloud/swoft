<?php

namespace app\controllers;

use app\models\logic\IndexLogic;
use swoft\base\ApplicationContext;
use swoft\log\FileHandler;
use swoft\log\Logger;
use swoft\rpc\RpcClient;
use swoft\service\Service;
use swoft\Swf;
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
     * @Inject
     * @var IndexLogic
     */
    private $logic;

    public function actionIndex(Request $request, Response $response)
    {
        $data = $this->logic->getUser();
        $data['params'] = Swf::$app->params();
        $data['count'] = Swf::$app->count;
        $data['request'] = $request->getRequestUri();
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


//        $dateFormat = "Y/m/d H:i:s";
//        $output = "%datetime% [%level_name%] [%channel%] [logid:ac135959afa9004e8617] [445(ms)] [4(MB)] [/Web/vrOrder/Order] [%extra%] [status=200] [] profile[] counting[]\n";
//        // finally, create a formatter
//        $formatter = new \Monolog\Formatter\LineFormatter($output, $dateFormat);
//
//        $logPath = RUNTIME_PATH."/my_app.log";
//
//        $stream = new FileHandler($logPath, [Logger::INFO]);
//        $stream->setFormatter($formatter);
//
//        // Create the logger
//        $logger = new Logger("user");
//        $logger->pushHandler($stream);

//        $logger = ApplicationContext::getBean('logger');
//
//        $logger->info("this is info");
//        $logger->info("this is info");
//        $logger->info("this is info");
//        $logger->flushLog();

        Swf::info("my info log");
        Swf::error("my error log");
        Swf::warning("my warning log");
        Swf::trace("my trace log");

        $this->render('/main/layout.html', $data);
    }

    public function actionRpc()
    {
//        /* @var RpcClient $client*/
//        $client = ApplicationContext::getBean('rpcClient');
//        $data = $client->rpcCall(RpcClient::USER_SERVICE, '/inner/uri', []);
//        $data = json_decode($data, true);

        $service = new Service("user");
        $ret = $service->call('/inner/uri', []);
//        $result = $ret->getResult();

        $data['count'] = Swf::$app->count;
        $this->outputJson($data);
    }
}
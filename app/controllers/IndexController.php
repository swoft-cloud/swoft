<?php

namespace app\controllers;

use app\models\logic\IndexLogic;
use DI\Annotation\Inject;
use swoft\base\ApplicationContext;
use swoft\cache\RedisClient;
use swoft\filter\FilterChain;
use swoft\http\HttpClient;
use swoft\log\FileHandler;
use swoft\log\Logger;
use swoft\service\Service;
use swoft\App;
use swoft\web\Controller;
use swoft\web\Request;
use swoft\web\Response;

/**
 * demo使用案例
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

    public function actionIndex()
    {
        $data = $this->logic->getUser();
        $data['properties'] = App::$properties['env'];

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

        App::getTimer()->addAfterTimer('afterTimer', 5000, [$this, 'testA']);

        $this->outputJson($data, 'suc');
    }

    public function testA()
    {
        App::trace("this trace timer");
        App::info("this trace info");
        App::debug("this trace debug");
        echo "after time do.................................\n";
    }

    public function actionRedis()
    {
        RedisClient::set('name', 'redis client stelin', 180);
        $name = RedisClient::get('name');
        RedisClient::get($name);

        $ret = RedisClient::deferCall('get', ['name']);
        $result = $ret->getResult();
        $data = [
            'redis' => $name,
            'defer' => $result
        ];
        $this->outputJson($data);
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

        App::trace("trace log");

        $this->render('/main/layout.html', $data);
    }

    public function actionLog()
    {
        // 标记开始
        App::profileStart("tag");

        // 直接输出异常
        App::error(new \Exception("error exception"));
        App::error("this errro log");
        App::info("this errro log");

        // 数组出
        App::error(['name' => 'boy']);
        App::debug("this errro log");

        // 标记结束
        App::profileEnd("tag");

        // 统计缓存命中率
        App::counting("cache", 1, 10);

        $this->outputJson([], 'suc');
    }

    public function actionRpc()
    {
        $result = Service::call("user", 'User::getUserInfo', [2,6,8]);

        $res = Service::deferCall("user", 'User::getUserInfo', [3,6,9]);
        $res2 = Service::deferCall("user", 'User::getUserInfo', [3,6,9]);
        $users = $res->getResult();
        $users2 = $res2->getResult();

        $data['count'] = App::$app->count;
        $data['ret'] = $result;
        $data['deferRet'] = $users;
        $data['deferRet2'] = $users2;
        $this->outputJson($data);
    }

    public function actionHttp()
    {
        $requestData = [
            'name' => 'boy',
            'desc' => 'php'
        ];

        $result = HttpClient::call("http://127.0.0.1/index/post?a=b", HttpClient::GET, $requestData);
        $data['result'] = $result;

        $ret = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
        $ret2 = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
        $data['deferResult'] = $ret->getResult();
        $data['deferResult2'] = $ret2->getResult();

        $this->outputJson($data);
    }

    public function actionPost(){
        $this->outputJson([
            'post' => $this->post(),
            'get' => $this->get()
        ], 'suc');
    }

    public function actionDemo()
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
        //$this->outputJson("data", 'suc');

        // 重定向一个URI
        $this->redirect("/login/index");
    }

    public function actionConfig()
    {
        $data = [];

        // 数组使用
        $version = App::$properties['version'];
        $data['version'] = $version;

        // 对象使用
        $service = App::$properties->get('service');
        $data['service'] = $service;

        //迭代器使用
        foreach (App::$properties as $key => $val) {
            $data['ary'][$key] = $val;
        }

        $this->outputJson($data, 'suc');
    }
}

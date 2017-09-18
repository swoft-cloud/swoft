<?php

namespace App\Controllers;

use App\Models\Logic\IndexLogic;
use Swoft\Cache\RedisClient;
use Swoft\Di\Annotation\AutoController;
use Swoft\Di\Annotation\Inject;
use Swoft\Di\Annotation\RequestMapping;
use Swoft\Http\HttpClient;
use Swoft\Service\Service;
use Swoft\App;
use Swoft\Web\Controller;

/**
 * demo使用案例
 *
 * @AutoController()
 *
 * @uses      IndexController
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class IndexController extends Controller
{
    /**
     *
     * @Inject()
     * @var IndexLogic
     */
    private $logic;

    /**
     * @RequestMapping()
     */
    public function actionIndex()
    {
        $data = $this->logic->getUser();

        $data['properties'] = App::$properties;

        App::profileStart("logger");
        App::profileStart("logger1");

        App::info("my info Log");
        App::info("my2 info Log");

        App::error("my error Log");
        App::warning("my warning Log");

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

    /**
     * @RequestMapping()
     */
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

    /**
     * @RequestMapping()
     */
    public function actionLogin()
    {

        $this->outputJson(array('login suc'), 'suc');
    }

    /**
     * @RequestMapping()
     */
    public function actionHtml()
    {
        $data = [
            'name' => 'stelin'
        ];

        App::profileStart("logger");

        App::info("my info Log");
        App::info("my2 info Log");

        App::error("my error Log");
        App::warning("my warning Log");

        App::pushlog("status", 200);

        App::profileEnd("logger");

        App::counting("redis.get", 1, 10);

        App::trace("trace Log");

        $this->render('/main/layout.html', $data);
    }

    /**
     * @RequestMapping()
     */
    public function actionLog()
    {
        // 标记开始
        App::profileStart("tag");

        // 直接输出异常
        App::error(new \Exception("error Exception"));
        App::error("this errro Log");
        App::info("this errro Log");

        // 数组出
        App::error(['name' => 'boy']);
        App::debug("this errro Log");

        // 标记结束
        App::profileEnd("tag");

        // 统计缓存命中率
        App::counting("Cache", 1, 10);

        $this->outputJson([], 'suc');
    }

    /**
     * @RequestMapping()
     */
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

    /**
     * @RequestMapping()
     */
    public function actionHttp()
    {
        $requestData = [
            'name' => 'boy',
            'desc' => 'php'
        ];

        $result = HttpClient::call("http://127.0.0.1/index/post?a=b", HttpClient::GET, $requestData);
        $result2 = HttpClient::call("http://www.baidu.com/", HttpClient::GET, $requestData);
        $data['result'] = $result;
        $data['result2'] = $result2;

        $ret = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
        $ret2 = HttpClient::deferCall("http://127.0.0.1/index/post", HttpClient::POST, $requestData);
        $data['deferResult'] = $ret->getResult();
        $data['deferResult2'] = $ret2->getResult();

        $this->outputJson($data);
    }

    /**
     * @RequestMapping()
     */
    public function actionPost()
    {
        $this->outputJson([
            'post' => $this->post(),
            'get' => $this->get()
        ], 'suc');
    }

    /**
     * @RequestMapping()
     */
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
        //$this->outputJson("Data", 'suc');

        // 重定向一个URI
        $this->redirect("/index/login");
    }

    /**
     * @RequestMapping()
     */
    public function actionConfig()
    {
        $data = [];

        // 数组使用
        $version = App::$properties['version'];
        $data['version'] = $version;

        // 对象使用
        $service = App::$properties->get('Service');
        $data['Service'] = $service;

        //迭代器使用
        foreach (App::$properties as $key => $val) {
            $data['ary'][$key] = $val;
        }

        $this->outputJson($data, 'suc');
    }
}

<?php

namespace swoft\web;

use swoft\App;
use swoft\base\Inotify;
use swoft\base\RequestContext;
use swoft\filter\FilterChain;
use swoft\helpers\ResponseHelper;

/**
 * 应用主体
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Application extends \swoft\base\Application
{
    /**
     * 初始化
     */
    public function init()
    {
        App::$app = $this;

        // 注册全局错误错误
        $this->registerErrorHandler();
    }

    public function doRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        // chrome两次请求bug修复
        if (isset($request->server['request_uri']) && $request->server['request_uri'] === '/favicon.ico') {
            $response->end('favicon.ico');
            return false;
        }

        // 请求数测试
        $this->count = $this->count + 1;

        $this->beforeRequest($request, $response);
        $swfRequest = RequestContext::getRequest();

        try {

            // 解析URI和method
            $uri = $swfRequest->getRequestUri();
            $method = $swfRequest->getMethod();

            // 运行controller
            $this->runController($uri, $method);

        } catch (\Exception $e) {
            App::getErrorHandler()->handlerException($e);
        }

        $this->after();
    }

    public function doReceive(\Swoole\Server $server, int $fd, int $from_id, string $data)
    {
        try {
            // 解包
            $packer = App::getPacker();
            $data = $packer->unpack($data);

            // 初始化
            $this->beforeReceiver($data);

            // 执行函数调用
            $response = $this->runService($data);
            $data = $packer->pack($response);

            // 处理完成
            $this->after();
        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $data = ResponseHelper::formatData("", $code, $message);
        }
        $server->send($fd, $data);
    }

    /**
     * 运行控制器
     *
     * @param string $uri
     * @param string $method
     *
     * @throws \Exception
     */
    public function runController(string $uri, string $method = "get")
    {
        /* @var Router $router */
        $router = App::getBean('router');

        // 路由解析
        App::profileStart("router.match");
        list($path, $info) = $router->match($uri, $method);
        App::profileEnd("router.match");

        // 路由未定义处理
        if ($info == null) {
            throw new \RuntimeException("路由不存在，uri=".$uri." method=".$method);
        }

        /* @var Controller $controller */
        list($controller, $actionId, $params) = $this->createController($path, $info);

        /* run controller with filters */
        $this->runControllerWithFilters($controller, $actionId, $params);
    }

    /**
     * onReceiver初始化
     *
     * @param array $data RPC包数据
     */
    private function beforeReceiver($data)
    {
        $logid = $data['logid'] ?? uniqid();
        $spanid = $data['spanid'] ?? 0;
        $uri = $data['func'] ?? "null";

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true),
        ];
        RequestContext::setContextData($contextData);
    }

    /**
     * onRequest初始化执行
     *
     * @param \Swoole\Http\Request  $request
     * @param \Swoole\Http\Response $response
     */
    private function beforeRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        RequestContext::setRequest($request);
        RequestContext::setResponse($response);

        // header获取日志ID和spanid请求跨度ID
        $logid = RequestContext::getRequest()->getHeader('logid', uniqid());
        $spanid = RequestContext::getRequest()->getHeader('spanid', 0);
        $uri = RequestContext::getRequest()->getRequestUri();

        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true),
        ];
        RequestContext::setContextData($contextData);
    }

    /**
     * run controller with filters
     *
     * @param Controller $controller 控制器
     * @param string     $actionId   actionID
     * @param array      $params     action参数
     */
    private function runControllerWithFilters(Controller $controller, string $actionId, array $params)
    {
        $request = App::getRequest();
        $response = App::getResponse();


        /* @var FilterChain $filter */
        $filter = App::getBean('filter');

        App::profileStart("filter");
        $result = $filter->doFilter($request, $response, $filter);
        App::profileEnd("filter");

        if ($result) {
            $response = $controller->run($actionId, $params);
            $response->send();
        }
    }

    /**
     * onRequest或onReceiver最后执行
     */
    private function after()
    {
        App::getLogger()->appendNoticeLog();
        RequestContext::destory();
    }

    /**
     * 获取basePath
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * 获取ViewPath
     *
     * @return string
     */
    public function getViewsPath()
    {
        return $this->viewsPath;
    }
}

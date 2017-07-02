<?php

namespace swoft\web;

use swoft\base\ApplicationContext;
use swoft\base\RequestAttributes;
use swoft\base\RequestContext;
use swoft\console\Console;
use swoft\filter\FilterChain;
use swoft\Swf;

/**
 *
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Application extends \swoft\base\Application
{
    private $tcp;
    private $http;
    private $swoft;
    private $setting;
    private $listen;

    use Console;

    public function start(){

        Swf::$app = $this;

        $this->swoft = new \Swoole\Http\Server($this->http['host'], $this->http['port'], $this->http['model'], $this->http['type']);

        $this->swoft->set($this->setting);
        $this->swoft->on('start', [$this, 'onStart']);
        $this->swoft->on('workerstart', [$this, 'onWorkerStart']);
        $this->swoft->on('managerstart', [$this, 'onManagerStart']);
        $this->swoft->on('request', [$this, 'onRequest']);

        if($this->tcp['enable'] == 1){
            $this->listen = $this->swoft->listen($this->tcp['host'], $this->tcp['port'], $this->tcp['type']);
            $this->listen->set([
                "open_eof_check"=>false,
                "package_max_length"=>20480
            ]);
            $this->listen->on('connect', [$this, 'onConnect']);
            $this->listen->on('receive', [$this, 'onReceive']);
            $this->listen->on('close', [$this, 'onClose']);
        }

        $this->swoft->start();
    }

    public function onConnect(\Swoole\Server $server, int $fd, int $from_id)
    {
        var_dump("connnect------");
    }

    public function onReceive(\Swoole\Server $server, int $fd, int $from_id, string $data)
    {
        var_dump("receiver---------*******************************__________________". $data);

        $data  = json_decode($data, true);
        $data['receiver'] = "ok";
        $data = json_encode($data);

        $server->send($fd, $data);
    }
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        var_dump("close------");
    }

    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        // chrome两次请求bug修复
        if(isset($request->server['request_uri']) && $request->server['request_uri'] == '/favicon.ico'){
            $response->end("favicon.ico");
            return false;
        }

        $this->count = $this->count + 1;

        echo "count= ".$this->count."---------\n";

        $this->beforeRequest($request, $response);
        $swfRequest = RequestContext::getRequest();
        $swfResponse = RequestContext::getResponse();

        try {

            /* @var UrlManager $urlMnanger*/
            $urlMnanger = ApplicationContext::getBean('urlManager');
            list($route, $params) = $urlMnanger->parseRequest($swfRequest);

            /* @var Controller $controller */
            list($controller, $actionId) = $this->createController($route);

            /* @var FilterChain $filter */
            $filter = ApplicationContext::getBean('filter');
            $filterHandler = $filter->doFilter($swfRequest, $swfResponse, $filter);

            /* run controller */
            $this->runController($filterHandler, $controller, $actionId, $params);
        } catch (\Exception $e) {
            $swfResponse->setResponseContent($e->getMessage());
            $swfResponse->send();
        }

        $this->afterRequest();
    }

    public function onStart(\Swoole\Http\Server $server)
    {
        file_put_contents($this->server['pfile'], $server->master_pid);
        file_put_contents($this->server['pfile'], ',' . $server->manager_pid, FILE_APPEND);
        swoole_set_process_name($this->server['pname']." master process (".$this->status['startFile'].")");
    }


    public function onManagerStart(\Swoole\Http\Server $server)
    {
        swoole_set_process_name($this->server['pname']." manager process");
    }

    public function onWorkerStart(\Swoole\Http\Server $server, int $workerId)
    {
        $setting = $server->setting;
        if($workerId >= $setting['worker_num']) {
            swoole_set_process_name($this->server['pname']. " task process");
        } else {
            swoole_set_process_name($this->server['pname']. " worker process");
        }
    }

    private function beforeRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        RequestContext::setRequest($request);
        RequestContext::setResponse($response);
    }

    private function runController($filterHandler, \swoft\web\Controller $controller, string $actionId, array $params)
    {
        if($filterHandler instanceof Response){
            $filterHandler->send();
        }else{
            $responseHandler = $controller->run($actionId, $params);
            $responseHandler->send();
        }
    }

    private function afterRequest()
    {
        RequestContext::destory();
    }

    public function params()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     *
     * @return string
     */
    public function getViewsPath()
    {
        return $this->viewsPath;
    }
}
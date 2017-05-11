<?php

namespace swoft\web;

use swoft\base\ApplicationContext;
use swoft\base\RequestAttributes;
use swoft\base\RequestContext;
use swoft\console\Console;

/**
 *
 *
 * @uses      Application
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
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
        $this->swoft = new \Swoole\Http\Server($this->http['host'], $this->http['port'], $this->http['model'], $this->http['type']);

        $this->swoft->set($this->setting);
        $this->swoft->on('start', [$this, 'onStart']);
        $this->swoft->on('workerstart', [$this, 'onWorkerStart']);
        $this->swoft->on('managerstart', [$this, 'onManagerStart']);
        $this->swoft->on('request', [$this, 'onRequest']);

        if($this->tcp['enable'] == 1){
            $this->listen = $this->swoft->listen($this->tcp['host'], $this->tcp['port'], $this->tcp['type']);
            $this->listen->on('connect', [$this, 'onConnect']);
            $this->listen->on('receive', [$this, 'onReceive']);
            $this->listen->on('close', [$this, 'onClose']);
            $this->listen->on('Packet', [$this, 'onPacket']);
        }

        $this->swoft->start();
    }

    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        // chrome两次请求bug修复
        if(isset($request->server['request_uri']) && $request->server['request_uri'] == '/favicon.ico'){
            $response->end("favicon.ico");
            return false;
        }
        $bTime = microtime(true);
        $this->beginRequest($request, $response);
        $eTime = microtime(true);

        try {

            /* @var UrlManager $urlMnanger*/
            $urlMnanger = ApplicationContext::getBean('urlManager');
            list($route, $params) = $urlMnanger->parseRequest($request);

            /* @var Controller $controller */
            list($controller, $actionId) = $this->createController($route);
            $controller->runAction($actionId, $params);
//            var_dump($controller, $actionId);

            $response->end('hello swoft2!'.sprintf("%.2f", (($eTime-$bTime))*1000));

        } catch (\Exception $e) {
            $response->end($e->getMessage().sprintf("%.2f", (($eTime-$bTime))*1000));
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

    private function beginRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        RequestContext::setRequest($request);
        RequestContext::setResponse($response);
    }

    private function afterRequest()
    {
        RequestContext::destory();
    }
}
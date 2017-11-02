<?php

namespace Swoft\Server;

use Swoft\App;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

/**
 * HTTP服务器
 *
 * @uses      HttpServer
 * @version   2017年10月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpServer extends RpcServer
{
    /**
     * @var \Swoole\Server\Port tcp监听器
     */
    protected $listen;

    /**
     * 启动Server
     */
    public function start()
    {
        // http server
        $this->server = new Server($this->httpSetting['host'], $this->httpSetting['port'], $this->httpSetting['model'], $this->httpSetting['type']);

        // 设置事件监听
        $this->server->set($this->setting);
        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('workerstart', [$this, 'onWorkerStart']);
        $this->server->on('managerstart', [$this, 'onManagerStart']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('pipeMessage', [$this, 'onPipeMessage']);
        $this->server->on('finish', [$this, 'onFinish']);

        // 启动RPC服务
        if ((int)$this->serverSetting['tcpable'] === 1) {
            $this->listen = $this->server->listen($this->tcpSetting['host'], $this->tcpSetting['port'], $this->tcpSetting['type']);
            $tcpSetting = $this->getListenTcpSetting();
            $this->listen->set($tcpSetting);
            $this->listen->on('connect', [$this, 'onConnect']);
            $this->listen->on('receive', [$this, 'onReceive']);
            $this->listen->on('close', [$this, 'onClose']);
        }

        $this->beforeStart();
        $this->server->start();
    }

    /**
     * http请求每次会启动一个协程
     *
     * @param Request  $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response)
    {
        App::getApplication()->doRequest($request, $response);
    }
}
<?php

namespace swoft\web;

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
        $response->end('hello swoft!');
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
}
<?php

namespace Swoft\Test\Web;


use Swoft\App;
use Swoft\Helper\ArrayHelper;
use Swoft\Testing\SwooleRequest as TestSwooleRequest;
use Swoft\Testing\SwooleResponse as TestSwooleResponse;

/**
 * @uses      AbstractTestCase
 * @version   2017-11-12
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractTestCase extends \Swoft\Test\AbstractTestCase
{

    const ACCEPT_VIEW = "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
    const ACCEPT_JSON = 'application/json';
    const ACCEPT_RAW = 'text/plain';

    /**
     * @param        $method
     * @param        $uri
     * @param array  $parameters
     * @param string $accept
     * @param array  $headers
     * @param string $rawContent
     *
     * @return bool|\Swoft\Testing\Web\Response
     */
    public function request($method, $uri, $parameters = [], $accept = self::ACCEPT_JSON, $headers = [], $rawContent = '')
    {
        $method = strtoupper($method);
        $swooleResponse = new TestSwooleResponse();
        $swooleRequest = new TestSwooleRequest();
        $swooleRequest->setRawContent($rawContent);
        $this->buildMockRequest($method, $uri, $parameters, $accept, $swooleRequest, $headers);
        return App::getDispatcherServer()->doDispatcher($swooleRequest, $swooleResponse);;
    }

    /**
     * @param       $method
     * @param       $uri
     * @param       $parameters
     * @param       $accept
     * @param       $swooleRequest
     * @param array $headers
     */
    protected function buildMockRequest($method, $uri, $parameters, $accept, $swooleRequest, $headers = [])
    {
        $urlAry = parse_url($uri);
        $urlParams = [];
        if(isset($urlAry['query'])){
            parse_str($urlAry['query'], $urlParams);
        }
        $defaultHeaders = [
            'host' => '127.0.0.1',
            "connection" => "keep-alive",
            "cache-control" => "max-age=0",
            "user-agent" => "PHPUnit",
            "upgrade-insecure-requests" => "1",
            "accept" => $accept,
            "dnt" => "1",
            "accept-encoding" => "gzip, deflate, br",
            "accept-language" => "zh-CN,zh;q=0.8,en;q=0.6,it-IT;q=0.4,it;q=0.2",
        ];

        $swooleRequest->fd = 1;
        $swooleRequest->header = ArrayHelper::merge($headers, $defaultHeaders);
        $swooleRequest->server = [
            "request_method" => $method,
            "request_uri" => $uri,
            "path_info" => "/",
            "request_time" => microtime(),
            "request_time_float" => microtime(true),
            "server_port" => 80,
            "remote_port" => 54235,
            "remote_addr" => "10.0.2.2",
            "master_time" => microtime(),
            "server_protocol" => "HTTP/1.1",
            "server_software" => "swoole-http-server",
        ];

        if ($method == 'GET') {
            $swooleRequest->get = $parameters;
        } elseif ($method == 'POST') {
            $swooleRequest->post = $parameters;
        }

        if (!empty($urlParams)) {
            $get = empty($swooleRequest->get) ? [] : $swooleRequest->get;
            $swooleRequest->get = array_merge($urlParams, $get);
        }
    }

}

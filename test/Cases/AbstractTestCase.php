<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Test\Cases;

use PHPUnit\Framework\TestCase;
use Swoft\App;
use Swoft\Helper\ArrayHelper;
use Swoft\Testing\SwooleRequest as TestSwooleRequest;
use Swoft\Testing\SwooleResponse as TestSwooleResponse;
use Swoft\Http\Message\Testing\Web\Request;
use Swoft\Http\Message\Testing\Web\Response;

/**
 * Class AbstractTestCase
 *
 * @package Swoft\Test\Cases
 */
class AbstractTestCase extends TestCase
{
    const ACCEPT_VIEW = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
    const ACCEPT_JSON = 'application/json';
    const ACCEPT_RAW = 'text/plain';

    /**
     * Send a mock request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param string $accept
     * @param array  $headers
     * @param string $rawContent
     * @return bool|\Swoft\Http\Message\Testing\Web\Response
     */
    public function request(
        string $method,
        string $uri,
        array $parameters = [],
        string $accept = self::ACCEPT_JSON,
        array $headers = [],
        string $rawContent = ''
    ) {
        $method = strtoupper($method);
        $swooleResponse = new TestSwooleResponse();
        $swooleRequest = new TestSwooleRequest();

        $this->buildMockRequest($method, $uri, $parameters, $accept, $swooleRequest, $headers);

        $swooleRequest->setRawContent($rawContent);

        $request = Request::loadFromSwooleRequest($swooleRequest);
        $response = new Response($swooleResponse);

        /** @var \Swoft\Http\Server\ServerDispatcher $dispatcher */
        $dispatcher = App::getBean('serverDispatcher');
        return $dispatcher->dispatch($request, $response);
    }

    /**
     * Send a mock json request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $headers
     * @param string $rawContent
     * @return bool|\Swoft\Http\Message\Testing\Web\Response
     */
    public function json(
        string $method,
        string $uri,
        array $parameters = [],
        array $headers = [],
        string $rawContent = ''
    ) {
        return $this->request($method, $uri, $parameters, self::ACCEPT_JSON, $headers, $rawContent);
    }

    /**
     * Send a mock view request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $headers
     * @param string $rawContent
     * @return bool|\Swoft\Http\Message\Testing\Web\Response
     */
    public function view(
        string $method,
        string $uri,
        array $parameters = [],
        array $headers = [],
        string $rawContent = ''
    ) {
        return $this->request($method, $uri, $parameters, self::ACCEPT_VIEW, $headers, $rawContent);
    }

    /**
     * Send a mock raw content request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $headers
     * @param string $rawContent
     * @return bool|\Swoft\Http\Message\Testing\Web\Response
     */
    public function raw(
        string $method,
        string $uri,
        array $parameters = [],
        array $headers = [],
        string $rawContent = ''
    ) {
        return $this->request($method, $uri, $parameters, self::ACCEPT_RAW, $headers, $rawContent);
    }

    /**
     * @param string               $method
     * @param string               $uri
     * @param array                $parameters
     * @param string               $accept
     * @param \Swoole\Http\Request $swooleRequest
     * @param array                $headers
     */
    protected function buildMockRequest(
        string $method,
        string $uri,
        array $parameters,
        string $accept,
        &$swooleRequest,
        array $headers = []
    ) {
        $urlAry = parse_url($uri);
        $urlParams = [];
        if (isset($urlAry['query'])) {
            parse_str($urlAry['query'], $urlParams);
        }
        $defaultHeaders = [
            'host'                      => '127.0.0.1',
            'connection'                => 'keep-alive',
            'cache-control'             => 'max-age=0',
            'user-agent'                => 'PHPUnit',
            'upgrade-insecure-requests' => '1',
            'accept'                    => $accept,
            'dnt'                       => '1',
            'accept-encoding'           => 'gzip, deflate, br',
            'accept-language'           => 'zh-CN,zh;q=0.8,en;q=0.6,it-IT;q=0.4,it;q=0.2',
        ];

        $swooleRequest->fd = 1;
        $swooleRequest->header = ArrayHelper::merge($headers, $defaultHeaders);
        $swooleRequest->server = [
            'request_method'     => $method,
            'request_uri'        => $uri,
            'path_info'          => '/',
            'request_time'       => microtime(),
            'request_time_float' => microtime(true),
            'server_port'        => 80,
            'remote_port'        => 54235,
            'remote_addr'        => '10.0.2.2',
            'master_time'        => microtime(),
            'server_protocol'    => 'HTTP/1.1',
            'server_software'    => 'swoole-http-server',
        ];

        if ($method == 'GET') {
            $swooleRequest->get = $parameters;
        } elseif ($method == 'POST') {
            $swooleRequest->post = $parameters;
        }

        if (! empty($urlParams)) {
            $get = empty($swooleRequest->get) ? [] : $swooleRequest->get;
            $swooleRequest->get = array_merge($urlParams, $get);
        }
    }
}
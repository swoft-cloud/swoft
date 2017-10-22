<?php

namespace Swoft\Http;

use Swoft\App;
use Swoole\Coroutine\Http\Client;

/**
 * HTTP调用，支持协程和同步两种客户端，底层自动实现切换
 *
 * @uses      HttpClient
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpClient extends AbstractHttpClient
{
    /**
     * http调用
     *
     * @param string $url     地址
     * @param string $method  方法,默认get
     * @param mixed  $data    数组格式传递数据
     * @param int    $timeout 超时时间，单位毫秒
     * @param array  $headers 请求header
     *                        <pre>
     *                        [
     *                        'Host' => "localhost",
     *                        "User-Agent" => 'Chrome/49.0.2587.3',
     *                        'Accept' => 'text/html,application/xhtml+xml,application/xml',
     *                        'Accept-Encoding' => 'gzip',
     *                        ]
     *                        </pre>
     *
     * @return mixed
     */
    public static function call(string $url, string $method = self::GET, $data, int $timeout = 200, array $headers = [])
    {
        if (App::isWorkerStatus() === false) {
            return CurlClient::call($url, $method, $data, $timeout, $headers);
        }

        $profileKey = $method . "." . $url;
        list($host, $port, $uri) = self::parseUrl($url);
        $headers = self::getRequestHeader($headers, $method);

        App::profileStart($profileKey);
        $client = new Client($host, $port);
        $client->setHeaders($headers);
        $client->set(['timeout' => $timeout]);
        $client->setMethod($method);

        if ($method != self::GET) {
            $client->setData(self::getContentData($data));
        } else {
            $buildQuery = self::getContentData($data);
            $uri .= "&" . $buildQuery;
        }

        $client->execute($uri);
        $result = $client->body;
        $client->close();
        App::profileEnd($profileKey);

        App::debug($profileKey . " result=" . $result);
        return $result;
    }

    /**
     * http延迟收包调用，可以用于并发请求
     *
     * @param string $url     地址
     * @param string $method  方法,默认get
     * @param mixed  $data    数组格式传递数据
     * @param int    $timeout 超时时间，单位毫秒
     * @param array  $headers 请求header
     *                        <pre>
     *                        [
     *                        'Host' => "localhost",
     *                        "User-Agent" => 'Chrome/49.0.2587.3',
     *                        'Accept' => 'text/html,application/xhtml+xml,application/xml',
     *                        'Accept-Encoding' => 'gzip',
     *                        ]
     *                        </pre>
     *
     * @return HttpResult
     */
    public static function deferCall(string $url, string $method = self::GET, $data, int $timeout = 200, array $headers = [])
    {
        $profileKey = $method . "." . $url;
        list($host, $port, $uri) = self::parseUrl($url);
        $headers = self::getRequestHeader($headers, $method);

        $client = new \Swoole\Coroutine\Http\Client($host, $port);
        $client->setHeaders($headers);
        $client->set(['timeout' => $timeout]);
        $client->setMethod($method);

        if ($method != self::GET) {
            $client->setData(self::getContentData($data));
        } else {
            $buildQuery = self::getContentData($data);
            $uri .= "&" . $buildQuery;
        }
        $client->setDefer();
        $result = $client->execute($uri);
        return new HttpResult(null, $client, $profileKey, $result);
    }

    /**
     * 解析url,目前还没支持dns解析
     *
     * @param string $url url地址，如：http://www.baidu.com
     *
     * @return array 返回解析结果
     */
    private static function parseUrl(string $url)
    {
        $defaultPorts = [
            'http'  => 80,
            'https' => 443
        ];

        $parses = parse_url($url);
        $protocol = $parses['scheme'];

        $host = $parses['host'];
        $path = $parses['path']?? "";
        $query = $parses['query']?? "";
        $uri = $path . "?" . $query;
        $port = $parses['port']?? $defaultPorts[$protocol];

        $host = self::dnsLookup($host);
        return [$host, $port, $uri];
    }

    /**
     * 域名dns查询
     *
     * @param string $host
     *
     * @return string
     */
    private static function dnsLookup(string $host)
    {
        $ipLong = ip2long($host);
        // ip
        if ($ipLong !== false) {
            return $host;
        }

        // 域名
        $ip = swoole_async_dns_lookup_coro($host);
        if (!$ip) {
            App::error("域名dns查询失败，domain=" . $host);
            throw new \InvalidArgumentException("域名dns查询失败，domain=" . $host);
        }
        return $ip;
    }
}

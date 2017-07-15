<?php

namespace swoft\http;

use swoft\App;

/**
 *
 *
 * @uses      HttpClient
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class HttpClient
{
    /**
     * @param string     $url
     * @param string     $method
     * @param array|null $data
     * @param int        $timeout
     * @param array      $headers
     * <pre>
     * [
     *   'Host' => "localhost",
     *   "User-Agent" => 'Chrome/49.0.2587.3',
     *   'Accept' => 'text/html,application/xhtml+xml,application/xml',
     *   'Accept-Encoding' => 'gzip',
     * ]
     * </pre>
     */
    public static function call(string $url, string $method, array $data = null, int $timeout = 200, array $headers = [])
    {
        $ip = "";
        $port = "";
        $uri = "";

        $client = new \Swoole\Coroutine\Http\Client('127.0.0.1', 80);
        $client->setHeaders($headers);
        $client->set([ 'timeout' => $timeout]);
        $client->setMethod($method);
        $client->setData($data);
        $result =  $client->body;
        $client->close();

        $packer = App::getPacker();
        $result = $packer->unpack($result);

        return $result;
    }

    public static function deferCall(string $url, string $method, array $data = null, int $timeout = 200, array $headers = [])
    {
        $ip = "";
        $port = "";
        $uri = "";
        $profileKey = "";
        $client = new \Swoole\Coroutine\Http\Client('127.0.0.1', 80);
        $client->setHeaders($headers);
        $client->set([ 'timeout' => $timeout]);
        $client->setMethod($method);
        $client->setData($data);
        $client->setDefer();

        return new HttpResult(null, $client, $profileKey);
    }
}
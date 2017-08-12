<?php

namespace swoft\web;

/**
 * Request请求对象
 *
 * @uses      Request
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Request extends \swoft\base\Request
{
    /**
     * 请求方法
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['request_method'];
    }

    /**
     * 请求path
     *
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->server['path_info'];
    }

    /**
     * 请求参数串
     *
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->server['query_string'];
    }

    /**
     * 请求URI
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->server['request_uri'];
    }

    /**
     * remote ip
     *
     * @return string
     */
    public function getRemoteIp()
    {
        return $this->server['remote_addr'];
    }

    /**
     * 获取所有cookes
     *
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookie;
    }

    /**
     * 获取所有header
     *
     * @return array
     * <pre>
     * [
     *  'host' => '192.168.99.100',
     *  'connection' => 'keep-alive',
     *  ...
     * ]
     * </pre>
     */
    public function getHeaders()
    {
        return $this->header;
    }

    /**
     * 获取header
     *
     * @param string $key       KEY名称
     * @param string $default   默认值
     *
     * @return string
     */
    public function getHeader(string $key, string $default = ""): string
    {
        if(isset($this->header[$key])){
            return $this->header[$key];
        }
        return $default;
    }

    /**
     * 获取用户user agent
     *
     * @param string $deafult 默认值
     *
     * @return string
     */
    public function getUserAgent(string $deafult = "")
    {
        if(isset($this->header['user-agent'])){
            return $this->header['user-agent'];
        }
        return $deafult;
    }
}
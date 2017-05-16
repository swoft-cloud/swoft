<?php

namespace swoft\web;

/**
 *
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
     * @param string $key
     * @return string
     */
    public function getHeader(string $key): string
    {

    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['request_method'];
    }

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->server['path_info'];
    }

    /**
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->server['query_string'];
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->server['request_uri'];
    }

    public function getRemoteIp()
    {
        return $this->server['remote_addr'];
    }

    public function getCookies(): array
    {

    }
    public function getCookie(string $name): string
    {

    }

    public function getUserAgent()
    {
        if(isset($this->request['header']['user-agent'])){

        }
    }
}
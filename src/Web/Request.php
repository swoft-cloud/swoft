<?php

namespace Swoft\Web;

/**
 * Request请求对象
 *
 * @uses      Request
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Request extends \Swoft\Base\Request
{
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
     * 获取用户user agent
     *
     * @param string $default 默认值
     *
     * @return string
     */
    public function getUserAgent(string $default = '')
    {
        if (isset($this->headers['user-agent'])) {
            return $this->headers['user-agent'];
        }

        return $default;
    }
}

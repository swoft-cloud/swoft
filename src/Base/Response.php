<?php

namespace Swoft\Base;

/**
 * 基类响应请求
 *
 * @uses      Response
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Response
{
    /**
     * swoole响应请求
     *
     * @var \Swoole\Http\Response
     */
    protected $response;

    /**
     * 初始化响应请求
     *
     * @param \Swoole\Http\Response $response
     */
    public function __construct(\Swoole\Http\Response $response)
    {
        $this->response = $response;
    }

    /**
     * 响应数据
     */
    public function send()
    {
    }

    /**
     * 重定向
     *
     * @param string   $url
     * @param null|int $status
     *
     * @return mixed
     */
    public function redirect($url, $status = null)
    {
        $this->response->header('Location', (string)$url);

        if (null === $status) {
            $status = 302;
        }

        if (null !== $status) {
            $this->response->status((int)$status);
        }

        return $this;
    }


    /**
     * Json 响应
     *
     * @param  mixed $data            The data
     * @param  int   $status          The HTTP status code.
     * @param  int   $encodingOptions Json encoding options
     *
     * @throws \RuntimeException
     * @return static
     */
    public function json($data, $status = null, $encodingOptions = 0)
    {
        $this->response->write($json = json_encode($data, $encodingOptions));

        // Ensure that the json encoding passed successfully
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        $this->response->header('Content-Type', 'application/json;charset=utf-8');

        if (null === $status) {
            $this->response->status((int)$status);
        }

        return $this;
    }
}

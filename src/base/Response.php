<?php

namespace swoft\base;

/**
 * 基类响应请求
 *
 * @uses      Response
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Response
{
    /**
     * swoole响应请求
     *
     * @var \Swoole\Http\Response
     */
    protected $response = null;

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
}
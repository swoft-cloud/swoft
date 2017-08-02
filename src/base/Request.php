<?php

namespace swoft\base;

/**
 * 请求request对象，每个请求实例化一个
 *
 * @uses      Request
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Request
{
    /**
     * @var \Swoole\Http\Request Swoole requet对象
     */
    protected $request = null;

    /**
     * @var array 请求headers
     */
    protected $header = [];

    /**
     * @var array 请求server
     */
    protected $server = [];

    /**
     * @var array 请求get参数
     */
    protected $get = [];

    /**
     * @var array 请求post参数
     */
    protected $post = [];

    /**
     * @var array 请求cookie
     */
    protected $cookie = [];

    /**
     * @var array 请求上传文件集合
     */
    protected $files = [];


    public function __construct(\Swoole\Http\Request $request)
    {
        $this->request = $request;
        $this->get = !property_exists($request, 'get') ? [] : $request->get;
        $this->post = !property_exists($request, 'post') ? [] : $request->post;
        $this->header = $request->header == null ? [] : $request->header;
        $this->server = $request->server == null ? [] : $request->server;
        $this->cookie = !property_exists($request, 'cookie') ? [] : $request->cookie;
        $this->files = !property_exists($request, 'files') ? [] : $request->files;
    }

    /**
     * 从GET/POST中获取一个参数，
     *
     * @param string $name     参数名称
     * @param mixed  $defatult 默认值
     *
     * @return mixed
     */
    public function getParameter(string $name, $defatult = null)
    {
        $params = $this->getParameters();
        if (isset($params[$name])) {
            return $params[$name];
        }
        return $defatult;
    }

    /**
     * 请求参数，等同$_REQUEST
     *
     * @return array
     */
    public function getParameters(): array
    {
        return array_merge($this->get, $this->post);
    }

    /**
     * GET参数，等同$_GET
     *
     * @return array
     */
    public function getGetParameters()
    {
        return $this->get;
    }

    /**
     * POST参数，等同$_POST
     *
     * @return array
     */
    public function getPostParameters()
    {
        return $this->post;
    }

    public function getCharacterEncoding(): string
    {

    }

    public function getContentLength(): int
    {

    }

    public function getContentType(): string
    {

    }
}
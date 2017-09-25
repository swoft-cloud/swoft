<?php

namespace Swoft\Base;

/**
 * 请求request对象，每个请求实例化一个
 * @uses      Request
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Request
{
    /**
     * @var \Swoole\Http\Request Swoole request对象
     */
    protected $request;

    /**
     * @var array 请求headers
     */
    protected $headers = [];

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

    /**
     * Request constructor.
     * @param \Swoole\Http\Request $request
     */
    public function __construct(\Swoole\Http\Request $request)
    {
        $this->request = $request;
        $this->get = $request->get ?? [];
        $this->post = $request->post ?? [];
        $this->headers = $request->header ?? [];
        $this->server = $request->server ?? [];
        $this->cookie = $request->cookie ?? [];
        $this->files = $request->files ?? [];
    }

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
     * 从GET/POST中获取一个参数，
     * @param string $name 参数名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getParameter(string $name, $default = null)
    {
        $params = $this->getParameters();

        if (isset($params[$name])) {
            return $params[$name];
        }

        return $default;
    }

    /**
     * 请求参数，等同$_REQUEST
     * @return array
     */
    public function getParameters(): array
    {
        return array_merge($this->get, $this->post);
    }

    /**
     * GET参数，等同$_GET
     * @param mixed $name
     * @param mixed $default
     * @return mixed
     */
    public function getQuery($name = null, $default = null)
    {
        if ($name === null) {
            return $this->get;
        }

        return $this->get[$name] ?? $default;
    }

    /**
     * GET参数，等同$_GET
     * @return array
     */
    public function getGetParameters()
    {
        return $this->get;
    }

    /**
     * GET参数，等同$_GET
     * @param string $name
     * @param mixed $default
     * @return array
     */
    public function getGetParameter($name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    /**
     * POST参数，等同$_POST
     * @param mixed $name
     * @param mixed $default
     * @return array
     */
    public function getPost($name = null, $default = null)
    {
        if ($name === null) {
            return $this->post;
        }

        return $this->post[$name] ?? $default;
    }

    /**
     * POST参数，等同$_POST
     * @return array
     */
    public function getPostParameters()
    {
        return $this->post;
    }

    /**
     * POST参数，等同$_POST
     * @param string $name
     * @param mixed $default
     * @return array
     */
    public function getPostParameter($name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return $this->isXhr();
    }

    /**
     * Is this an XHR request?
     * Note: This method is not part of the PSR-7 standard.
     * @return bool
     */
    public function isXhr()
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    public function getCharacterEncoding(): string
    {
    }

    public function getContentLength(): int
    {
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->getHeader('Content-Type');
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
        return $this->headers;
    }

    /**
     * 获取header
     *
     * @param string $key       KEY名称
     * @param string $default   默认值
     *
     * @return string
     */
    public function getHeader(string $key, string $default = ''): string
    {
        $key = strtolower($key);

        return $this->headers[$key] ?? $default;
    }

}

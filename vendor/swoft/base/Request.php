<?php

namespace swoft\base;

/**
 *
 *
 * @uses      Request
 * @version   2017年05月11日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class Request
{
    protected $request = null;
    protected $header = [];
    protected $server = [];
    protected $get = [];
    protected $post = [];
    protected $cookie = [];
    protected $files = [];


    public function __construct(\Swoole\Http\Request $request)
    {
        $this->request = $request;
        $this->get = $request->get;
        $this->post = $request->post == null ? [] : $request->post;
        $this->header = $request->header == null ? [] : $request->header;
        $this->server = $request->server == null ? [] : $request->server;
        $this->cookie = $request->cookie == null ? [] : $request->cookie;
        $this->files = $request->files == null ? [] : $request->files;
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

    /**
     *
     * @param $name
     * @return mixed
     */
    public function getParameter($name, $defatult = null)
    {
        $params = $this->getParameters();
        if(isset($params[$name])){
            return $params[$name];
        }
        return $defatult;
    }

    public function getParameters(): array
    {
        return array_merge($this->get, $this->post);
    }
}
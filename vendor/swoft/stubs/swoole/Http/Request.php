<?php

namespace Swoole\Http;
/**
 * Http请求对象
 * Class swoole_http_request
 */
class Request
{
    public $get;
    public $post;
    public $header;
    public $server;
    public $cookie;
    public $files;

    public $fd;

    /**
     * 获取非urlencode-form表单的POST原始数据
     * @return string
     */
    function rawContent()
    {
    }
}
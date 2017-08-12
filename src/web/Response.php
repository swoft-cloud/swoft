<?php

namespace swoft\web;

/**
 * 响应response
 *
 * @uses      Response
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Response extends \swoft\base\Response
{
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    private $status = 200;
    private $charset = "utf-8";
    private $responseContent = "";
    private $format = self::FORMAT_HTML;

    /**
     * @var \Exception 未知异常
     */
    private $exception = null;


    /**
     * 输出contentTypes集合
     *
     * @var array
     */
    private $contentTypes = [
        self::FORMAT_XML => 'text/xml',
        self::FORMAT_HTML => 'text/html',
        self::FORMAT_JSON => 'application/json',
    ];

    /**
     * 显示数据
     */
    public function send()
    {
        $this->formatContentType();
        $this->response->status($this->status);
        $this->response->end($this->responseContent);
    }

    /**
     * 添加header
     *
     * @param string $name
     * @param string $value
     */
    public function addHeader(string $name, string $value)
    {
        $this->response->header($name, $value);
    }

    /**
     * 设置Http code
     *
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * 设置格式json/html/xml...
     *
     * @param string $format
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * charset设置
     *
     * @param string $charset
     */
    public function setCharset(string $charset){
        $this->charset = $charset;
    }

    /**
     * 获取异常
     *
     * @return \Exception 异常
     */
    public function getException(): \Exception
    {
        return $this->exception;
    }

    /**
     * 设置异常
     *
     * @param \Exception $exception 初始化异常
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * 设置返回内容
     *
     * @param string $responseContent
     */
    public function setResponseContent(string $responseContent)
    {
        $this->responseContent = $responseContent;
    }

    /**
     * 添加cookie
     *
     * @param string  $key
     * @param  string $value
     * @param int     $expire
     * @param string  $path
     * @param string  $domain
     */
    public function addCookie($key, $value, $expire = 0, $path = '/', $domain = '')
    {
        $this->response->cookie($key, $value, $expire, $path, $domain);
    }

    /**
     * 格式化contentType
     */
    private function formatContentType()
    {
        // contentType
        $contentType = $this->contentTypes[$this->format];
        $contentType .= ";charset=".$this->charset;

        $this->response->header('Content-Type', $contentType);
    }
}
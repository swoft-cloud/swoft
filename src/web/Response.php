<?php

namespace swoft\web;

/**
 *
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
    private $contentTypes = [
        self::FORMAT_XML => 'text/xml',
        self::FORMAT_HTML => 'text/html',
        self::FORMAT_JSON => 'application/json',
    ];

    public function send()
    {
        $this->formatContentType();
        $this->response->status($this->status);
        $this->response->end($this->responseContent);
    }

    public function addHeader(string $name, string $value)
    {
        $this->response->header($name, $value);
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    public function setCharset(string $charset){
        $this->charset = $charset;
    }

    /**
     * @param string $responseContent
     */
    public function setResponseContent(string $responseContent)
    {
        $this->responseContent = $responseContent;
    }

    public function addCookie($key, $value, $expire = 0, $path = '/', $domain = '')
    {
        $this->response->cookie($key, $value, $expire, $path, $domain);
    }

    private function formatContentType()
    {
        // contentType
        $contentType = $this->contentTypes[$this->format];
        $contentType .= ";charset=".$this->charset;

        $this->response->header('Content-Type', $contentType);
    }
}
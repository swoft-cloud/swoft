<?php

namespace swoft\web;

/**
 *
 *
 * @uses      Response
 * @version   2017年05月11日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class Response extends \swoft\base\Response
{
    const FORMAT_RAW = 'raw';
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';
    const FORMAT_XML = 'xml';

    private $format = self::FORMAT_HTML;
    private $charset = "UTF-8";
    private $status = 200;
    private $headers;


    public function addHeader(string $name, string $value)
    {

    }

    public function setStatus(int $status)
    {

    }

    public function setContentType(string $type)
    {

    }

    public function setCharset(string $charset){

    }

    public function addCookie(string $name, string $value)
    {

    }

    /**
     *
     */
    public function send()
    {

    }
}
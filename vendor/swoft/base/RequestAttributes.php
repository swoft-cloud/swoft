<?php

namespace swoft\base;

/**
 *
 *
 * @uses      RequestAttributes
 * @version   2017年04月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestAttributes
{
    private $request;
    private $response;

    public function __construct(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        var_dump($request->get);
        var_dump($request->post);
    }

    public function getRequest()
    {

    }

    public function getResponse(){

    }
}
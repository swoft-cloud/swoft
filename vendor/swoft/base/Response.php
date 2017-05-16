<?php

namespace swoft\base;

/**
 *
 *
 * @uses      Response
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Response
{
    protected $response = null;

    public function __construct(\Swoole\Http\Response $response)
    {
        $this->response = $response;
    }

    public function send()
    {

    }
}
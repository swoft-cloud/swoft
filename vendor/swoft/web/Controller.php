<?php

namespace swoft\web;

use swoft\base\RequestContext;

/**
 *
 *
 * @uses      Controller
 * @version   2017年04月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class Controller extends \swoft\base\Controller
{
    public function redirect(string $uri, array $params = [])
    {
        $this->run($uri, $params);
    }

    public function render(string $templateId, array $data = [])
    {
        $content = "hellow twigs !";
        RequestContext::getResponse()->setResponseContent($content);
    }

    public function outputJson($data = "", $message = '', $status = 200)
    {
        $json = json_encode([
            'data'       => $data,
            'status'     => $status,
            'message'    => $message,
            'serverTime' => microtime(true)
        ]);

        $response = RequestContext::getResponse();
        $response->setFormat(Response::FORMAT_JSON);
        $response->setResponseContent($json);
    }
}
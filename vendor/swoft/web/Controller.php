<?php

namespace swoft\web;

use swoft\base\RequestContext;
use swoft\Swf;

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
        $viewsPath = Swf::$app->getViewsPath();

        $this->checkTemplateFile($viewsPath, $templateId);
        $content = $this->renderContent($viewsPath, $templateId, $data);
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

    private function renderContent(string $viewsPath, string $templateId, array $data)
    {
        $loader = new \Twig_Loader_Filesystem($viewsPath);
        $twig = new \Twig_Environment($loader);
        return $twig->render($templateId, $data);
    }

    private function checkTemplateFile(string $viewsPath, string $templateId)
    {
        $file = $viewsPath.$templateId;
        if(!file_exists($file)){
            throw new \Exception($file.' is not founded!');
        }
    }
}
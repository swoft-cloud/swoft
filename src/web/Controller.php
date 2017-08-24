<?php

namespace swoft\web;

use app\beans\filters\CommonParamsFilter;
use app\beans\filters\LoginFilter;
use swoft\base\RequestContext;
use swoft\App;
use swoft\helpers\ResponseHelper;

/**
 * 控制器
 *
 * @uses      Controller
 * @version   2017年04月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Controller extends \swoft\base\Controller
{
    /**
     * 重定向
     *
     * @param string $uri
     * @param array  $params
     */
    public function redirect(string $uri, array $params = [])
    {
//        $this->run($uri, $params);
    }

    /**
     * 数据模板显示
     *
     * @param string $templateId
     * @param array  $data
     */
    public function render(string $templateId, array $data = [])
    {
//        $viewsPath = App::$app->getViewsPath();
//
//        $this->checkTemplateFile($viewsPath, $templateId);
//        $content = $this->renderContent($viewsPath, $templateId, $data);
        RequestContext::getResponse()->setResponseContent(json_encode($data));
    }

    /**
     * json格式输出
     *
     * @param mixed  $data      数据
     * @param string $message   文案
     * @param int    $status    状态，200成功，非200失败
     */
    public function outputJson($data = "", $message = '', $status = 200)
    {
        $data = ResponseHelper::formatData($data, $message, $status);
        $json = json_encode($data);

        $response = RequestContext::getResponse();
        $response->setFormat(Response::FORMAT_JSON);
        $response->setResponseContent($json);
    }

    /**
     * 输出文件内容
     *
     * @param string $viewsPath     模板路径
     * @param string $templateId    模板ID
     * @param array  $data          数据
     *
     * @return string   返回渲染数据
     */
    private function renderContent(string $viewsPath, string $templateId, array $data)
    {
        return $data;
    }

    /**
     * 模板文件路径检查
     *
     * @param string $viewsPath     模板路径
     * @param string $templateId    模板ID
     *
     * @throws \InvalidArgumentException
     */
    private function checkTemplateFile(string $viewsPath, string $templateId)
    {
        $file = $viewsPath.$templateId;
        if(!file_exists($file)){
            App::error($file."模板文件不存在");
            throw new \InvalidArgumentException($file."模板文件不存在");
        }
    }
}
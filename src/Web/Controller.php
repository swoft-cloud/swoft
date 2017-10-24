<?php

namespace Swoft\Web;

use App\beans\Filters\CommonParamsFilter;
use App\beans\Filters\LoginFilter;
use Swoft\Base\RequestContext;
use Swoft\App;
use Swoft\Helper\ResponseHelper;

/**
 * 控制器
 *
 * @uses      Controller
 * @version   2017年04月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Controller extends \Swoft\Base\Controller
{
    use ViewRendererTrait;

    /**
     * alias of the `outputJson()`
     * @see Controller::outputJson()
     * {@inheritdoc}
     */
    public function renderJson($data = '', $message = '', $status = 200)
    {
        $this->outputJson($data, $message, $status);
    }

    /**
     * json格式输出
     *
     * @param mixed  $data      数据
     * @param string $message   文案
     * @param int    $status    状态，200成功，非200失败
     */
    public function outputJson($data = '', $message = '', $status = 200)
    {
        $data = ResponseHelper::formatData($data, $message, $status);
        $json = json_encode($data);

        $response = RequestContext::getResponse();
        $response->setFormat(Response::FORMAT_JSON);
        $response->setResponseContent($json);
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
        if (!file_exists($file)) {
            App::error($file."模板文件不存在");
            throw new \InvalidArgumentException($file."模板文件不存在");
        }
    }

    /**
     * getRenderer
     * @return ViewRenderer
     */
    public function getRenderer()
    {
        return App::getBean('renderer');
    }

    /**
     * @param string $view
     * @return string
     */
    protected function resolveView(string $view)
    {
        return App::getAlias($view);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-19
 * Time: 9:12
 */

namespace Swoft\Web;

use Swoft\Base\RequestContext;

/**
 * Trait ViewRendererTrait
 * @package Swoft\Web
 *
 * @uses      ViewRenderer helper
 * @version   2017年08月14日
 * @author    inhere <in.798@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
trait ViewRendererTrait
{
    /**
     * getRenderer
     * @return ViewRenderer
     */
    abstract public function getRenderer();

    /**
     * @param string $view
     * @return string
     */
    protected function resolveView(string $view)
    {
        return $view;
    }

    /*********************************************************************************
     * view method
     *********************************************************************************/

    /**
     * @param string $view
     * @param array $data
     * @param null|string $layout
     */
    public function render(string $view, array $data = [], $layout = null)
    {
        $result = $this->getRenderer()->render($this->resolveView($view), $data, $layout);

        $response = RequestContext::getResponse();
        $response->setResponseContent($result);
    }

    /**
     * @param string $view
     * @param array $data
     */
    public function renderPartial($view, array $data = [])
    {
        $result = $this->getRenderer()->fetch($this->resolveView($view), $data);

        $response = RequestContext::getResponse();
        $response->setResponseContent($result);
    }

    /**
     * @param string $string
     * @param array $data
     */
    public function renderContent($string, array $data = [])
    {
        $result = $this->getRenderer()->renderContent($string, $data);

        $response = RequestContext::getResponse();
        $response->setResponseContent($result);
    }

}
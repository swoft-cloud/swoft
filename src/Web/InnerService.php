<?php

namespace Swoft\Web;

use Swoft\App;
use Swoft\Helpers\ResponseHelper;

/**
 * 内部服务基类
 *
 * @uses      InnerService
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class InnerService
{
    /**
     * 执行内部服务调用
     *
     * @param string $method 方法名称
     * @param array  $params 调用函数参数
     *
     * @return array
     */
    public function run(string $method, array $params)
    {
        // 服务之前调用
        $this->beforeService($method, $params);

        if (!method_exists($this, $method)) {
            App::error("内部服务方法不可调用，method=" . $method);
            throw new \BadMethodCallException("内部服务方法不可调用，method=" . $method);
        }
        $data = $this->$method(...$params);
        $data = ResponseHelper::formatData($data);

        // 服务之后调用
        $this->afterService($method, $params);

        return $data;
    }

    /**
     * 服务之前调用
     *
     * @param string $method 方法名称
     * @param array  $params 调用函数参数
     */
    public function beforeService(string $method, array $params)
    {

    }

    /**
     * 服务之后调用
     *
     * @param string $method 方法名称
     * @param array  $params 调用函数参数
     */
    public function afterService(string $method, array $params)
    {

    }
}
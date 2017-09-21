<?php

namespace Swoft\Bean\Annotation;

/**
 * action方法注解
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @uses      RequestMapping
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestMapping
{
    /**
     * action路由规则
     *
     * @var string
     */
    private $route = "";

    /**
     * 路由支持的HTTP方法集合
     *
     * @var array
     */
    private $method = [RequestMethod::GET, RequestMethod::POST];

    /**
     * RequestMapping constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->route = $values['value'];
        }
        if (isset($values['route'])) {
            $this->route = $values['route'];
        }

        if (isset($values['method'])) {
            $method = $values['method'];
            $this->method = !is_array($method) ? [$method] : $method;
        }
    }

    /**
     * 获取路由
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * 获取方法集合
     *
     * @return array
     */
    public function getMethod()
    {
        return $this->method;
    }
}

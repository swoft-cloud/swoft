<?php

namespace swoft\di\annotation;

/**
 * action 注解
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @uses      RequestMapping
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RequestMapping
{
    /**
     * @var string
     */
    private $route = "";

    /**
     * @var mixed
     */
    private $method = [RequestMethod::GET, RequestMethod::POST];

    public function __construct(array $values)
    {
        if(isset($values['value'])){
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
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }
}
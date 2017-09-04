<?php

namespace Swoft\Di;

/**
 * 资源数据代理器
 *
 * @uses      ResourceDataProxy
 * @version   2017年09月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ResourceDataProxy
{
    /**
     * properties.php配置信息
     *
     * @var array
     */
    public $properties = [];

    /**
     * 已解析的路由规则
     *
     * @var array
     */
    public $requestMapping = [];

    /**
     * 监听器
     *
     * @var array
     */
    public $listeners = [];

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }
}
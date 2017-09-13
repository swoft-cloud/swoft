<?php

namespace Swoft\Di\Resource;

/**
 * 抽象bean资源
 *
 * @uses      AbstractResource
 * @version   2017年08月21日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractResource implements IResource
{
    /**
     * properties.php配置
     *
     * @var array
     */
    protected $properties = [];

    /**
     * 非数组格式属性值转换
     *
     * @param $property
     *
     * @return array
     */
    public function getTransferProperty($property)
    {
        if (!is_string($property)) {
            return [$property, 0];
        }

        // 正则匹配
        $injectProperty = $property;
        $isRef = preg_match('/^\$\{(.*)\}$/', $property, $match);

        // 解析
        if (!empty($match)) {
            $isRef = strpos($match[1], 'config') === 0 ? 0 : $isRef;
            $injectProperty = $this->getInjectProperty($match[1]);
        }

        return [$injectProperty, $isRef];
    }

    /**
     * 属性值引用解析
     *
     * @param string $property
     *
     * @return mixed|string
     */
    public function getInjectProperty(string $property)
    {
        // '${beanName}'格式解析
        $propertyKeys = explode(".", $property);
        if (count($propertyKeys) == 1) {
            return $property;
        }

        if ($propertyKeys[0] != 'config') {
            throw new \InvalidArgumentException("properties配置引用格式不正确，key=" . $propertyKeys[0]);
        }

        // '${config.xx.yy}' 格式解析,直接key
        $propertyKey = str_replace("config.", "", $property);
        if (isset($this->properties[$propertyKey])) {
            return $this->properties[$propertyKey];
        }

        // '${config.xx.yy}' 格式解析, 层级解析
        $layerProperty = "";
        unset($propertyKeys[0]);

        foreach ($propertyKeys as $subPropertyKey) {
            if (isset($this->properties[$subPropertyKey])) {
                $layerProperty = $this->properties[$subPropertyKey];
                continue;
            }
            if (!isset($layerProperty[$subPropertyKey])) {
                throw new \InvalidArgumentException("$subPropertyKey is not exisit configed");
            }
            $layerProperty = $layerProperty[$subPropertyKey];
        }

        return $layerProperty;
    }
}

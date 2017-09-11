<?php

namespace Swoft\Di;

/**
 *
 *
 * @uses      Collector
 * @version   2017年09月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Collector
{
    /**
     * properties.php配置信息
     *
     * @var array
     */
    private static $properties = [];

    /**
     * 已解析的路由规则
     *
     * @var array
     */
    public static $requestMapping = [];

    /**
     * 监听器
     *
     * @var array
     */
    public static $listeners = [];

    /**
     * 表结构实体
     *
     * @var array
     */
    public static $entities = [];

    /**
     * @return array
     */
    public static function getProperties(): array
    {
        return self::$properties;
    }

    /**
     * @param array $properties
     */
    public  static function setProperties(array $properties)
    {
        self::$properties = $properties;
    }
}
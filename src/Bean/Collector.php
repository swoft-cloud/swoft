<?php

namespace Swoft\Bean;

/**
 * 注解数据收集器
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
     * 已解析的路由规则
     *
     * @var array
     */
    public static $requestMapping = [];

    /**
     * 已解析的Service映射
     *
     * @var array
     */
    public static $serviceMapping = [];

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
     * 定时任务列表
     *
     * @var array
     */
    public static $crontab = [];
}
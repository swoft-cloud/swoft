<?php

namespace swoft\helpers;

use DI\Container;

/**
 *
 *
 * @uses      BeanFactory
 * @version   2017年04月25日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 北京尤果网文化传媒有限公司
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class BeanFactory
{
    /**
     * @var Container
     */
    private static $container;


    /**
     *
     * @param $name
     *
     * @return mixed
     */
    public static function getBean($name)
    {
        return self::$container->get($name);
    }

    /**
     * whether contain a bean by name
     *
     * @param string $name
     * @return bool
     */
    public static function containsBean($name)
    {
        return self::$container->has($name);
    }

    /**
     * @param Container $container
     */
    public static function setContainer($container)
    {
        self::$container = $container;
    }
}
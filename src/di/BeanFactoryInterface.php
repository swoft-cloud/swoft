<?php
namespace swoft\di;

/**
 * bean工程
 *
 * @uses      BeanFactoryInterface
 * @version   2017年07月07日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface BeanFactoryInterface
{
    public static function getBean(string $name);
    public static function createBean(string $beanName, array $definition);
    public static function hasBean(string $name);
}
<?php
namespace swoft\di;

/**
 *
 *
 * @uses      BeanFactoryInterface
 * @version   2017年07月07日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface BeanFactoryInterface
{
    public static function getContainer();
    public static function get(string $name);
}
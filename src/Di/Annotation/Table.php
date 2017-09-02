<?php

namespace Swoft\Di\Annotation;

/**
 * 表注解
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @uses      Table
 * @version   2017年08月31日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Table
{
    /**
     * 表名
     *
     * @var string
     */
    private $name;
}
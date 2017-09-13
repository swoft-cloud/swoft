<?php

namespace Swoft\Di\Annotation;

/**
 * 表列注解
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @uses      Column
 * @version   2017年08月31日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Column
{
    /**
     * 名称
     *
     * @var string
     */
    private $name;

    /**
     * 类型
     *
     * @var string
     */
    private $type = "string";

    /**
     * 默认值
     *
     * @var mixed
     */
    private $default = "";

    /**
     * 长度
     *
     * @var int
     */
    private $length = -1;


    public function __construct()
    {
    }
}

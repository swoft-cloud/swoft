<?php

namespace Swoft\Bean\Annotation;

/**
 * bean类型
 *
 * @uses      Scope
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
final class Scope
{
    /**
     * 单例
     */
    const SINGLETON = 1;

    /**
     * 每次创建一个新实例
     */
    const PROTOTYPE = 2;
}

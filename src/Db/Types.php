<?php

namespace Swoft\Db;

/**
 * 实体类型
 *
 * @uses      Types
 * @version   2017年09月12日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Types
{
    /**
     * 整数
     */
    const INT = 'int';

    /**
     * 非负整数
     */
    const NUMBER = 'number';

    /**
     * 字符串
     */
    const STRING = 'string';

    /**
     * 浮点型
     */
    const FLOAT = 'float';

    /**
     * 时间类型字符串
     */
    const DATETIME = 'datetime';

    /**
     * bool类型
     */
    const BOOLEAN = 'boolean';
}
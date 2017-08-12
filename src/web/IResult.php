<?php

namespace swoft\web;

/**
 * 数据返回接口
 *
 * @uses      IResult
 * @version   2017年07月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IResult
{
    public function getResult();
}
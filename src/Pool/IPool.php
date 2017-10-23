<?php

namespace Swoft\Pool;

/**
 * 连接池接口
 *
 * @uses      Pool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IPool
{
    /**
     * 获取连接
     *
     * @return mixed
     */
    public function getConnect();

    /**
     * 释放连接
     *
     * @param object $connect
     */
    public function release($connect);
}

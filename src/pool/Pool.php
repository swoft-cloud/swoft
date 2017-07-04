<?php

namespace swoft\pool;

/**
 *
 *
 * @uses      Pool
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface Pool
{
    public function getConnect();

    public function release($connect);
}
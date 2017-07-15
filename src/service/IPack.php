<?php

namespace swoft\service;

/**
 *
 *
 * @uses      IPack
 * @version   2017年07月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IPack
{
    public function pack($data);
    public function unpack($data);
    public function formatData($func, $params);
    public function checkData($data);
}
<?php

namespace swoft\filter;

/**
 * uriPattern接口
 *
 * @uses      IUriPattern
 * @version   2017年08月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IUriPattern
{
    public function isMatch(string $uri, string $uriPattern): bool;
}
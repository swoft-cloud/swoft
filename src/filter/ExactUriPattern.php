<?php

namespace swoft\filter;

/**
 * 完全精确匹配
 *
 * @uses      ExactUriPattern
 * @version   2017年08月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ExactUriPattern implements IUriPattern
{
    /**
     * 精确匹配
     * 例如：/a/b,/c/d/e.html
     *
     * @param string $uri
     * @param string $uriPattern
     *
     * @return bool
     */
    public function isMatch(string $uri, string $uriPattern): bool
    {
        $reg = str_replace('/', '\/', $uriPattern);
        $reg = '/^'.$reg.'$/';
        $matchResult = preg_match($reg, $uri);
        if($matchResult !== 1){
            return false;
        }
        return true;
    }
}
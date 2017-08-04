<?php

namespace swoft\filter;

/**
 * 扩展名匹配
 *
 * @uses      ExtUriPattern
 * @version   2017年08月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ExtUriPattern implements IUriPattern
{

    /**
     * 扩展名称配置
     * 例如: *.html *.php *.action
     *
     * @param string $uri
     * @param string $uriPattern
     *
     * @return bool
     */
    public function isMatch(string $uri, string $uriPattern): bool
    {
        $reg = '/^\*\.([a-z-A-Z-0-9]*)$/';
        $result = preg_match($reg, $uriPattern, $match);
        if ($result !== 1) {
            return false;
        }

        $matchReg = '/.*\.' . $match[1] . '/';
        $matchResult = preg_match($matchReg, $uri);
        if ($matchResult !== 1) {
            return false;
        }
        return true;
    }
}
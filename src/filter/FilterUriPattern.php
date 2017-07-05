<?php

namespace swoft\filter;

/**
 *
 *
 * @uses      FilterUriPattern
 * @version   2017年05月28日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class FilterUriPattern
{
    /**
     * uriPattern规则匹配
     *
     * @param string $uri
     * @param string $uriPattern
     *
     * @return bool
     */
    public function match(string $uri, string $uriPattern): bool
    {
        if ($this->isMatchExt($uri, $uriPattern)
            || $this->isMatchPath($uri, $uriPattern)
            || $this->isMatchExact($uri, $uriPattern)
        ) {
            return true;
        }
        return false;
    }

    /**
     * 扩展名称配置
     * 例如: *.html *.php *.action
     *
     * @param string $uri
     * @param string $uriPattern
     *
     * @return bool
     */
    private function isMatchExt(string $uri, string $uriPattern): bool
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

    /**
     * 路径匹配
     * 例如: /a/b/*,/a/*,/*(所有路径)
     *
     * @param string $uri
     * @param string $uriPattern
     *
     * @return bool
     */
    private function isMatchPath(string $uri, string $uriPattern): bool
    {
        $reg = '/^(.*)\*$/';
        $result = preg_match($reg, $uriPattern, $match);
        if ($result !== 1) {
            return false;
        }

        $pathReg = $match[1];
        $pathReg = str_replace('/', '\/', $pathReg);
        $matchReg = '/^' . $pathReg . '/';

        $matchResult = preg_match($matchReg, $uri);
        if ($matchResult !== 1) {
            return false;
        }
        return true;
    }

    /**
     * 精确匹配
     * 例如：/a/b,/c/d/e.html
     *
     * @param string $uri
     * @param string $uriPattern
     *
     * @return bool
     */
    private function isMatchExact(string $uri, string $uriPattern): bool
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